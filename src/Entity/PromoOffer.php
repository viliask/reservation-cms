<?php

namespace App\Entity;

use App\Repository\PromoOfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=PromoOfferRepository::class)
 * @Serializer\ExclusionPolicy("all")
 */
class PromoOffer
{
    const RESOURCE_KEY = 'promos';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=55)
     * @Serializer\Expose()
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Expose()
     */
    private $discount;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     */
    private $minDays;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Expose()
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Expose()
     */
    private $endDate;

    /**
     * @ORM\ManyToMany(targetEntity=Room::class, inversedBy="promoOffers")
     * @ORM\JoinTable(name="promo_offer_room",
     *     joinColumns={@ORM\JoinColumn(onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(onDelete="CASCADE")}
     * )
     */
    private $rooms;

    /**
     * @ORM\Column(type="string", length=10)
     * @Serializer\Expose()
     */
    private $locale;


    public function __construct()
    {
        $this->rooms = new ArrayCollection();
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

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getMinDays(): ?int
    {
        return $this->minDays;
    }

    public function setMinDays(int $minDays): self
    {
        $this->minDays = $minDays;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return Collection|Room[]
     */
    public function getRooms():? Collection
    {
        return $this->rooms;
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

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
