<?php

namespace App\Controller\Website;

use App\Controller\Traits\CommonTrait;
use App\Entity\Event;
use App\Entity\Room;
use App\Form\Type\EventType;
use App\Form\Type\ReservationType;
use App\Repository\EventRepository;
use DateTime;
use DateTimeImmutable;
use Sulu\Bundle\MediaBundle\Media\Manager\MediaManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/room")
 */
class RoomController extends AbstractController
{
    use CommonTrait;

    /**
     * @Route("/confirmation", name="room_confirmation", methods={"GET"})
     */
    public function confirmation(): Response
    {
        return $this->render('room/confirmation.html.twig');
    }

    /**
     * @Route("/{id}/{checkIn}/{checkOut}", name="room_show_dates", methods={"GET", "POST"})
     */
    public function showWithDates(
        Room $room,
        Request $request,
        string $checkIn,
        string $checkOut,
        MediaManagerInterface $mediaManager
    ): Response {
        $availabilityForm = $this->createForm(ReservationType::class);
        $event            = new Event();
        $eventForm        = $this->createForm(EventType::class, $event);
        $checkInDate      = new DateTimeImmutable($checkIn);
        $checkOutDate     = new DateTimeImmutable($checkOut);
        $guests           = $request->query->get('guests');

        $availabilityForm->get('checkInDate')->setData($checkInDate);
        $availabilityForm->get('checkOutDate')->setData($checkOutDate);
        $availabilityForm->get('guests')->setData($guests);
        $eventForm->get('checkIn')->setData($checkInDate);
        $eventForm->get('checkOut')->setData($checkOutDate);
        $eventForm->get('guests')->setData($guests);

        $params              = $this->createParams($room, $eventForm, $event, $request, $availabilityForm, $mediaManager);
        $params['checked']   = '';
        $params              += $this->findPromoOffer($room, $checkIn, $checkOut);
        $params['basePrice'] = $room->getBasePrice();
        $params['stepsAmount'] = $room->getStepsAmount();
        $params['stepsDiscount'] = $room->getStepsDiscount();

        return $this->render('room/show.html.twig', $params);
    }

    /**
     * @Route("/{id}", name="room_show", methods={"GET", "POST"})
     */
    public function show(Room $room, Request $request, MediaManagerInterface $mediaManager): Response
    {
        $availabilityForm = $this->createForm(ReservationType::class);
        $event            = new Event();
        $eventForm        = $this->createForm(EventType::class, $event);

        $params = $this->createParams($room, $eventForm, $event, $request, $availabilityForm, $mediaManager);

        return $this->render('room/show.html.twig', $params);
    }

    private function createParams(
        Room $room,
        FormInterface $eventForm,
        Event $event,
        Request $request,
        FormInterface $availabilityForm,
        MediaManagerInterface $mediaManager
    ): array {
        $eventForm->get('rooms')->setData([$room]);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            return $this->processForm($event);
        }

        return
            [
                'room'             => $room,
                'form'             => $eventForm->createView(),
                'availabilityForm' => $availabilityForm->createView(),
            ] + $this->getMedia($room, $mediaManager);
    }

    private function processForm(Event $event)
    {
        $event->setLocale('pl');
        $event->setStatus('draft');
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($event);
        $entityManager->flush();

        return $this->redirectToRoute('room_confirmation');
    }

    /**
     * @Route("/availability/{id}/{checkIn}/{checkOut}", name="xhr_room_availability", options={"expose"=true}, methods="GET")
     */
    public function checkRoomAvailability(
        Room $room,
        string $checkIn,
        string $checkOut,
        EventRepository $eventRepository
    ): JsonResponse {
        $availableRoom  = $eventRepository->findAvailableRooms($checkIn, $checkOut, $room->getId());
        $discountParams = null;

        if ($availableRoom) {
            /** @var Room $roomObject */
            $roomObject = $availableRoom[0];
            if ($roomObject->getName() === $room->getName()) {
                $status         = true;
                $discountParams = $this->findPromoOffer($roomObject, $checkIn, $checkOut);
            } else {
                $status = false;
            }
        } else {
            $status = false;
        }

        return $this->json(
            [
                'checkIn'   => $checkIn,
                'checkOut'  => $checkOut,
                'status'    => $status,
                'basePrice' => $room->getBasePrice(),
                'stepsAmount' => $room->getStepsAmount(),
                'stepsDiscount' => $room->getStepsDiscount(),
            ] + $discountParams
        );
    }

    private function findPromoOffer(Room $room, string $checkIn, string $checkOut)
    {
        $checkInDate  = new DateTime($checkIn);
        $checkOutDate = new DateTime($checkOut);
        $daysBetween  = date_diff($checkInDate, $checkOutDate)->d;
        $offers       = $room->getPromoOffers();
        $fairDiscount = 0;
        $discountName = null;

        foreach ($offers as $offer) {
            $startDate = $offer->getStartDate();
            $endDate   = $offer->getEndDate();
            if ($checkInDate >= $startDate && $checkInDate <= $endDate && $checkOutDate <= $endDate && $startDate <= $checkOutDate) {
                $discount = $offer->getDiscount();
                if ($discount >= $fairDiscount && $daysBetween >= $offer->getMinDays()) {
                    $fairDiscount = $discount;
                    $discountName = $offer->getName();
                }
            }
        }

        return ['discount' => $fairDiscount, 'discountName' => $discountName];
    }
}
