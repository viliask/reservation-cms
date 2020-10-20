<?php

namespace App\Service;

use App\Entity\Room;

class RoomTypeSelect
{
    const DEFAULT = Room::ROOM_TYPE;

    /**
     * @var array
     */
    private $roomTypes;

    public function __construct(Room $room)
    {
        $this->roomTypes = $room->getTypesArray();
    }

    public function getDefault(){
        return RoomTypeSelect::DEFAULT;
    }

    public function getValues(): array
    {
        $values = [];
        foreach ($this->roomTypes as $value) {
            $values[] = [
                'name' => $value,
            ];
        }

        return $values;
    }
}
