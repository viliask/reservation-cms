<?php

declare(strict_types=1);

namespace App\Content;

use App\Entity\Room;
use App\Repository\RoomRepository;
use Sulu\Component\Content\Compat\PropertyInterface;
use Sulu\Component\Content\SimpleContentType;

class RoomSelectionContentType extends SimpleContentType
{
    /**
     * @var RoomRepository
     */
    private $roomRepository;

    public function __construct(RoomRepository $roomRepository)
    {
        parent::__construct('room_selection');

        $this->roomRepository = $roomRepository;
    }

    /**
     * @return Room[]
     */
    public function getContentData(PropertyInterface $property): array
    {
        $ids = $property->getValue();
        $locale = $property->getStructure()->getLanguageCode();

        $rooms = [];
        foreach ($ids ?: [] as $id) {
            $room = $this->roomRepository->findById((int) $id, $locale);
            if ($room && $room->isEnabled()) {
                $rooms[] = $room;
            }
        }

        return $rooms;
    }

    /**
     * {@inheritdoc}
     */
    public function getViewData(PropertyInterface $property)
    {
        return $property->getValue();
    }
}
