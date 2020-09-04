<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Event
{
    const RESOURCE_KEY = 'events';

    /**
     * @var int|null
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     * @Serializer\Expose()
     */
    private $enabled;

    /**
     * @var \DateTimeImmutable|null
     *
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Serializer\Expose()
     */
    private $checkIn;

    /**
     * @var \DateTimeImmutable|null
     *
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Serializer\Expose()
     */
    private $checkOut;

    /**
     * @var EventTranslation[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\EventTranslation", mappedBy="event", cascade={"ALL"}, indexBy="locale")
     *
     * @Serializer\Exclude
     */
    private $translations;

    /**
     * @ORM\Column(type="string", length=10)
     * @Serializer\Expose()
     */
    private $locale;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     */
    private $mail;

    /**
     * @ORM\Column(type="smallint")
     * @Serializer\Expose()
     */
    private $guests;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Expose()
     */
    private $message;

    /**
     * @ORM\Column(type="string", length=50)
     * @Serializer\Expose()
     */
    private $status;

    /**
     * @ORM\Column(type="boolean")
     * @Serializer\Expose()
     */
    private $policy;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Serializer\Expose()
     */
    private $price;

    /**
     * @ORM\ManyToMany(targetEntity=Room::class, inversedBy="reservations")
     */
    private $rooms;

    public function __construct()
    {
        $this->enabled = false;
        $this->translations = new ArrayCollection();
        $this->rooms = new ArrayCollection();
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("rooms")
     */
    public function getRoomIds(): ArrayCollection
    {
        return $this->rooms->map(function (Room $room) {
            return $room->getId();
        });
    }

    /**
     * @return Collection|Room[]
     */
    public function getRooms():? Collection
    {
        return $this->rooms;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getCheckIn(): ?\DateTimeImmutable
    {
        return $this->checkIn;
    }

    public function getFormattedCheckIn()
    {
        if (!$checkIn = $this->getCheckIn()) {
            return null;
        }

        return $checkIn->format('Y-m-d H:i:s');
    }

    public function setCheckIn(?\DateTimeImmutable $checkIn): self
    {
        $this->checkIn = $checkIn;

        return $this;
    }

    public function getCheckOut(): ?\DateTimeImmutable
    {
        return $this->checkOut;
    }

    public function getFormattedCheckOut()
    {
        if (!$checkOut = $this->getCheckOut()) {
            return null;
        }

        return $checkOut->format('Y-m-d H:i:s');
    }

    public function setCheckOut(?\DateTimeImmutable $checkOut): self
    {
        $this->checkOut = $checkOut;

        return $this;
    }

    /**
     * @Serializer\VirtualProperty(name="title")
     */
    public function getTitle(): ?string
    {
        $translation = $this->getTranslation($this->locale);
        if (!$translation) {
            return null;
        }

        return $translation->getTitle();
    }

    public function setTitle(string $title): self
    {
        $translation = $this->getTranslation($this->locale);
        if (!$translation) {
            $translation = $this->createTranslation($this->locale);
        }

        $translation->setTitle($title);

        return $this;
    }

    /**
     * @Serializer\VirtualProperty(name="teaser")
     */
    public function getTeaser(): ?string
    {
        $translation = $this->getTranslation($this->locale);
        if (!$translation) {
            return null;
        }

        return $translation->getTeaser();
    }

    public function setTeaser(string $teaser): self
    {
        $translation = $this->getTranslation($this->locale);
        if (!$translation) {
            $translation = $this->createTranslation($this->locale);
        }

        $translation->setTeaser($teaser);

        return $this;
    }

    /**
     * @Serializer\VirtualProperty(name="description")
     */
    public function getDescription(): ?string
    {
        $translation = $this->getTranslation($this->locale);
        if (!$translation) {
            return null;
        }

        return $translation->getDescription();
    }

    public function setDescription(string $description): self
    {
        $translation = $this->getTranslation($this->locale);
        if (!$translation) {
            $translation = $this->createTranslation($this->locale);
        }

        $translation->setDescription($description);

        return $this;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return EventTranslation[]
     */
    public function getTranslations(): array
    {
        return $this->translations->toArray();
    }

    protected function getTranslation(string $locale): ?EventTranslation
    {
        if (!$this->translations->containsKey($locale)) {
            return null;
        }

        return $this->translations->get($locale);
    }

    protected function createTranslation(string $locale): EventTranslation
    {
        $translation = new EventTranslation($this, $locale);
        $this->translations->set($locale, $translation);

        return $translation;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getGuests(): ?int
    {
        return $this->guests;
    }

    public function setGuests(int $guests): self
    {
        $this->guests = $guests;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPolicy(): ?bool
    {
        return $this->policy;
    }

    public function setPolicy(bool $policy): self
    {
        $this->policy = $policy;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function addRoom(?Room $room): self
    {
        if ($room && !$this->rooms->contains($room)) {
            $this->rooms[] = $room;
        }

        return $this;
    }

    public function removeRoom(Room $room): self
    {
        if ($this->rooms->contains($room)) {
            $this->rooms->removeElement($room);
        }

        return $this;
    }
}
