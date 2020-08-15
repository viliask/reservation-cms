<?php

namespace App\Controller\Website;

use App\Entity\Event;
use App\Entity\Room;
use App\Form\Type\EventType;
use App\Form\Type\ReservationType;
use App\Repository\EventRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/room")
 */
class RoomController extends AbstractController
{
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
    public function showWithDates(Room $room, Request $request, string $checkIn, string $checkOut): Response
    {
        $event        = new Event();
        $form         = $this->createForm(EventType::class, $event);
        $checkInDate  = new DateTimeImmutable($checkIn);
        $checkOutDate = new DateTimeImmutable($checkOut);
        $guests       = $request->query->get('guests');

        $form->get('checkIn')->setData($checkInDate);
        $form->get('checkOut')->setData($checkOutDate);
        $form->get('guests')->setData($guests);
        $form->get('rooms')->setData([$room]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setLocale('pl');
            $event->setStatus('draft');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('room_confirmation');
        }

        return $this->render(
            'room/showAvailable.html.twig',
            [
                'room'     => $room,
                'form'     => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="room_show", methods={"GET", "POST"})
     */
    public function show(Room $room, Request $request): Response
    {
        $form  = $this->createForm(ReservationType::class);

        if ($form->isSubmitted() && $form->isValid()) {

        }

        return $this->render(
            'room/show.html.twig',
            [
                'room' => $room,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/availability/{id}/{checkIn}/{checkOut}", name="xhr_room_availability", options={"expose"=true}, methods="GET")
     */
    public function checkRoomAvailability(Room $room, string $checkIn, string $checkOut, Request $request, EventRepository $eventRepository): JsonResponse
    {
        $availableRoom = $eventRepository->findAvailableRooms($checkIn, $checkOut, $room->getId());

        /** @var Room $roomObject */
        $roomObject = $availableRoom ? $availableRoom[0] : null;
        $status = $roomObject->getName() === $room->getName() ? true : false;

        return $this->json(['room' => $roomObject->getId(), 'checkIn' => $checkIn, 'checkOut' => $checkOut, 'status' => $status]);
    }
}
