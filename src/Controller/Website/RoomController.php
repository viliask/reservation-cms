<?php

namespace App\Controller\Website;

use App\Entity\Event;
use App\Entity\Room;
use App\Form\Type\EventType;
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
     * @Route("/{id}", name="room_show", methods={"GET"})
     */
    public function show(Room $room, Request $request): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();
        }

        return $this->render('room/show.html.twig', [
            'room' => $room,
            'form' => $form->createView(),
        ]);
    }
}
