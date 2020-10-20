<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Room;
use App\Repository\RoomRepository;

class RoomNavigationGenerator
{
    /**
     * @var RoomRepository
     */
    private $repository;

    public function __construct(RoomRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Room[]
     */
    public function generateRoomsList()
    {
        return $this->repository->findRooms();
    }

    /**
     * @return Room[]
     */
    public function generateApartmentsList()
    {
        return $this->repository->findApartments();
    }
}
