<?php

namespace App\Controller\Website;

use App\Entity\Event;
use App\Entity\Room;
use App\Form\Type\EventType;
use App\Form\Type\ReservationType;
use App\Repository\EventRepository;
use DateTimeImmutable;
use Sulu\Bundle\MediaBundle\Media\Manager\MediaManagerInterface;
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
    public function showWithDates(Room $room, Request $request, string $checkIn, string $checkOut, MediaManagerInterface $mediaManager): Response
    {
        $availabilityForm = $this->createForm(ReservationType::class);
        $event            = new Event();
        $eventForm        = $this->createForm(EventType::class, $event);
        $checkInDate  = new DateTimeImmutable($checkIn);
        $checkOutDate = new DateTimeImmutable($checkOut);
        $guests       = $request->query->get('guests');

        $availabilityForm->get('checkInDate')->setData($checkInDate);
        $availabilityForm->get('checkOutDate')->setData($checkInDate);
        $availabilityForm->get('guests')->setData($guests);
        $eventForm->get('checkIn')->setData($checkInDate);
        $eventForm->get('checkOut')->setData($checkOutDate);
        $eventForm->get('guests')->setData($guests);
        $eventForm->get('rooms')->setData([$room]);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            return $this->processForm($event);
        }

        $pageMedia     = [];
        $roomIndicator = str_replace(' ', '-', strtolower($room->getName()));
        foreach($mediaManager->get('en') as $media ) {
            if (str_contains($media->getTitle(), $roomIndicator) && str_contains($media->getMimeType(), 'image')) {
                $pageMedia[] =
                    [
                        'media' => $media,
                        'title' => $media->getTitle(),
                        'index' => substr($media->getTitle(), -1),
                    ];
            }
        }

        usort($pageMedia, function ($a, $b) {
            return $a['index'] <=> $b['index'];
        });

        return $this->render(
            'room/show.html.twig',
            [
                'room'             => $room,
                'form'             => $eventForm->createView(),
                'availabilityForm' => $availabilityForm->createView(),
                'media'            => $pageMedia,
                'checked'          => '',
            ]
        );
    }

    /**
     * @Route("/{id}", name="room_show", methods={"GET", "POST"})
     */
    public function show(Room $room, Request $request, MediaManagerInterface $mediaManager): Response
    {
        $availabilityForm = $this->createForm(ReservationType::class);
        $event            = new Event();
        $eventForm        = $this->createForm(EventType::class, $event);

        $eventForm->get('rooms')->setData([$room]);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            return $this->processForm($event);
        }

        $pageMedia     = [];
        $roomIndicator = str_replace(' ', '-', strtolower($room->getName()));
        foreach($mediaManager->get('en') as $media ) {
            if (str_contains($media->getTitle(), $roomIndicator) && str_contains($media->getMimeType(), 'image')) {
                $pageMedia[] =
                    [
                        'media' => $media,
                        'title' => $media->getTitle(),
                        'index' => substr($media->getTitle(), -1),
                    ];
            }
        }

        usort($pageMedia, function ($a, $b) {
            return $a['index'] <=> $b['index'];
        });

        return $this->render(
            'room/show.html.twig',
            [
                'room'             => $room,
                'form'             => $eventForm->createView(),
                'availabilityForm' => $availabilityForm->createView(),
                'media'            => $pageMedia,
            ]
        );
    }

    protected function processForm(Event $event)
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
        $availableRoom = $eventRepository->findAvailableRooms($checkIn, $checkOut, $room->getId());

        if ($availableRoom) {
            /** @var Room $roomObject */
            $roomObject = $availableRoom[0];
            if ($roomObject->getName() === $room->getName()) {
                $status = true;
            } else {
                $status = false;
            }
        } else {
            $status = false;
        }

        return $this->json(
            ['checkIn' => $checkIn, 'checkOut' => $checkOut, 'status' => $status]
        );
    }
}
