<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoomRepository::class)
 */
class Room
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="smallint")
     */
    private $maxGuests;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, mappedBy="rooms")
     */
    private $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMaxGuests(): ?int
    {
        return $this->maxGuests;
    }

    public function setMaxGuests(int $maxGuests): self
    {
        $this->maxGuests = $maxGuests;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Event $event): self
    {
        if (!$this->reservations->contains($event)) {
            $this->reservations[] = $event;
            $event->addRoom($this);
        }

        return $this;
    }

    public function removeReservation(Event $event): self
    {
        if ($this->reservations->contains($event)) {
            $this->reservations->removeElement($event);
            $event->removeRoom($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
