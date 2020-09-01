<?php

namespace App\Controller\Traits;

use App\Entity\Room;
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
}
