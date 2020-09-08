<?php

namespace App\Controller\Traits;

use App\Entity\Event;
use App\Entity\PromoOffer;
use App\Entity\Room;
use App\Repository\RoomRepository;
use Sulu\Bundle\MediaBundle\Media\Manager\MediaManagerInterface;

trait CommonTrait
{
    private function getMedia(Room $room, MediaManagerInterface $mediaManager): array
    {
        $pageMedia     = [];
        $roomIndicator = str_replace(' ', '-', strtolower($room->getName()));

        foreach ($mediaManager->get('en') as $media) {
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

        return ['media' => $pageMedia];
    }

    /**
     * Deletion and addition rooms to single promo offer entity
     *
     * @param array                $data
     * @param Event|PromoOffer     $entity
     * @param RoomRepository       $roomRepository
     */
    private function processRooms(array $data, $entity, RoomRepository $roomRepository)
    {
        if ($roomIds = $data['rooms'] ?? null) {
            $allRooms = [];
            $actual   = [];

            foreach ($entity->getRooms() as $promoRoom){
                $allRooms[] = $promoRoom->getId();
            }
            foreach ($roomIds as $id) {
                $room = $roomRepository->findById($id, 'en');
                $actual[] = $room->getId();
                $entity->addRoom($room);
            }
            $toDelete = array_diff($allRooms, $actual);
            foreach ($toDelete as $delete) {
                $entity->removeRoom($roomRepository->find($delete));
            }
        }

        if (empty($data['rooms'])) {
            foreach ($entity->getRooms() as $room){
                $entity->removeRoom($room);
            }
        }
    }
}
