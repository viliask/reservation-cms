<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=RoomRepository::class)
 */
class Room
{
    const RESOURCE_KEY = 'rooms';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $enabled;

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

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $locale;

    /**
     * @var RoomTranslation[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\RoomTranslation", mappedBy="room", cascade={"ALL"}, indexBy="locale")
     *
     * @Serializer\Exclude
     */
    private $translations;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $widgetHeader;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $widgetText;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="integer")
     */
    private $basePrice;

    /**
     * @ORM\ManyToMany(targetEntity=PromoOffer::class, mappedBy="rooms")
     */
    private $promoOffers;

    public function __construct()
    {
        $this->enabled = false;
        $this->translations = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->promoOffers = new ArrayCollection();
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
     * @return Collection|Room[]
     */
    public function getReservation(): Collection
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
     * @return RoomTranslation[]
     */
    public function getTranslations(): array
    {
        return $this->translations->toArray();
    }

    protected function getTranslation(string $locale): ?RoomTranslation
    {
        if (!$this->translations->containsKey($locale)) {
            return null;
        }

        return $this->translations->get($locale);
    }

    protected function createTranslation(string $locale): RoomTranslation
    {
        $translation = new RoomTranslation($this, $locale);
        $this->translations->set($locale, $translation);

        return $translation;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getWidgetHeader(): ?string
    {
        return $this->widgetHeader;
    }

    public function setWidgetHeader(?string $widgetHeader): self
    {
        $this->widgetHeader = $widgetHeader;

        return $this;
    }

    public function getWidgetText(): ?string
    {
        return $this->widgetText;
    }

    public function setWidgetText(?string $widgetText): self
    {
        $this->widgetText = $widgetText;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getBasePrice(): ?int
    {
        return $this->basePrice;
    }

    public function setBasePrice(int $basePrice): self
    {
        $this->basePrice = $basePrice;

        return $this;
    }

    /**
     * @return Collection|PromoOffer[]
     */
    public function getPromoOffers(): Collection
    {
        return $this->promoOffers;
    }

    public function addPromoOffer(PromoOffer $promoOffer): self
    {
        if (!$this->promoOffers->contains($promoOffer)) {
            $this->promoOffers[] = $promoOffer;
        }

        return $this;
    }

    public function removePromoOffer(PromoOffer $promoOffer): self
    {
        if ($this->promoOffers->contains($promoOffer)) {
            $this->promoOffers->removeElement($promoOffer);
        }

        return $this;
    }
}
