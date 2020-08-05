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
     * @Route("/{id}", name="room_show", methods={"POST"})
     */
    public function show(Room $room, Request $request): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);

        $checkIn = new DateTimeImmutable($request->query->get('checkIn'));
        $checkOut = new DateTimeImmutable($request->query->get('checkOut'));

        $form->get('checkIn')->setData($checkIn);
        $form->get('checkOut')->setData($checkOut);
        $form->get('rooms')->setData([$room]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();
        }

        return $this->render('room/show.html.twig', [
            'room' => $room,
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
            'form' => $form->createView(),
        ]);
    }
}
