<?php

namespace App\DataFixtures;

use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RoomFixtures extends Fixture
{
    public const ROOM_REFERENCE = 'room-';

    public function load(ObjectManager $manager)
    {
        $room = new Room();
        $room->setName('Big room');
        $room->setMaxGuests(5);
        $room->setEnabled(false);
        $room->setTitle('Test');
        $this->addReference(self::ROOM_REFERENCE.'0', $room);

        $manager->persist($room);
        $manager->flush();
    }
}
