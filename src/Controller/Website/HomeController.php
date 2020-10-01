<?php

namespace App\Controller\Website;

use App\Controller\Traits\CommonTrait;
use App\Entity\Room;
use App\Form\Type\ReservationType;
use App\Repository\EventRepository;
use App\Repository\RoomRepository;
use Sulu\Bundle\MediaBundle\Media\Manager\MediaManagerInterface;
use Sulu\Bundle\WebsiteBundle\Controller\WebsiteController;
use Sulu\Component\Content\Compat\StructureInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HomeController extends WebsiteController
{
    use CommonTrait;

    const RESERVATION_PATH = 'reservation';

    public function indexAction(StructureInterface $structure, RoomRepository $roomRepository, MediaManagerInterface $mediaManager, bool $preview = false, bool $partial = false): Response
    {
        $attributes = [];
        $form       = $this->createForm(
            ReservationType::class,
            null,
            ['action' => $this->generateUrl(self::RESERVATION_PATH)]
        );
        $media         = [];
        $featuredRooms = $roomRepository->findEnabled();

        /* @var $room Room */
        foreach ($featuredRooms as $room) {
            $media[$room->getId()] = $this->getMedia($room, $mediaManager);
        }

        $attributes['form'] = $form->createView();
        $attributes['rooms'] = $featuredRooms;
        $attributes['media'] = $media;

        $response = $this->renderStructure(
            $structure,
            $attributes,
            $preview,
            $partial
        );

        return $response;
    }

    public function reservation(Request $request, EventRepository $eventRepository, MediaManagerInterface $mediaManager): Response
    {
        $reservation = $request->request->get('reservation');
        $checkIn     = $reservation['checkInDate'];
        $checkOut    = $reservation['checkOutDate'];
        $guests      = $reservation['guests'];
        $rooms       = $eventRepository->findAvailableRooms($checkIn, $checkOut, null, (int)$guests);
        $media       = [];

        /* @var $room Room */
        foreach ($rooms as $room) {
            $media[$room->getId()] = $this->getMedia($room, $mediaManager);
        }

        return $this->render(
            '/room/index.html.twig',
            [
                'rooms'    => $rooms,
                'checkIn'  => $checkIn,
                'checkOut' => $checkOut,
                'guests'   => $guests,
                'media'    => $media,
            ]
        );
    }
}
