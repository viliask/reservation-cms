<?php

namespace App\DataFixtures;

use App\Entity\Event;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class EventFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public const EVENT_REFERENCE = 'event-';

    public function load(ObjectManager $manager)
    {
        $event = new Event();
        $event->setPrice(100);
        $event->setStatus('Waiting for payment');
        $event->setMessage('Test');
        $event->setMail('test@test.com');
        $event->setPhone('123123123');
        $event->setGuests(3);
        $event->setFirstName('John');
        $event->setLastName('Doe');
        $event->setCheckIn(new \DateTimeImmutable('2010-10-10'));
        $event->setCheckOut(new \DateTimeImmutable('2010-10-15'));
        $event->setEnabled(false);
        $event->setTitle('test');
        $this->addReference(self::EVENT_REFERENCE.'0', $event);
        $event->addRoom($this->getReference('room-0'));

        $manager->persist($event);
        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return array(
            RoomFixtures::class
        );
    }
}
