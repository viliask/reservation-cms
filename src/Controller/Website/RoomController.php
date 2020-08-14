<?php

namespace App\Controller\Website;

use App\Entity\Event;
use App\Entity\Room;
use App\Form\Type\EventType;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $event    = new Event();
        $form     = $this->createForm(EventType::class, $event);
        $checkInDate  = new DateTimeImmutable($checkIn);
        $checkOutDate = new DateTimeImmutable($checkOut);
        $guests   = $request->query->get('guests');

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
                'checkIn'  => $checkIn,
                'checkOut' => $checkOut,
                'form'     => $form->createView(),
            ]
        );
    }
}
