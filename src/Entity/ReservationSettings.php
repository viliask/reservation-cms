<?php

namespace App\Entity;

use App\Repository\ReservationSettingsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationSettingsRepository::class)
 */
class ReservationSettings
{
    const RESOURCE_KEY = 'settings';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $summerStart;

    /**
     * @ORM\Column(type="datetime")
     */
    private $summerEnd;

    /**
     * @ORM\Column(type="smallint")
     */
    private $priceModifier;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $locale;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    public function __construct()
    {
        $this->enabled = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSummerStart(): ?\DateTimeInterface
    {
        return $this->summerStart;
    }

    public function setSummerStart(\DateTimeInterface $summerStart): self
    {
        $this->summerStart = $summerStart;

        return $this;
    }

    public function getSummerEnd(): ?\DateTimeInterface
    {
        return $this->summerEnd;
    }

    public function setSummerEnd(\DateTimeInterface $summerEnd): self
    {
        $this->summerEnd = $summerEnd;

        return $this;
    }

    public function getPriceModifier(): ?int
    {
        return $this->priceModifier;
    }

    public function setPriceModifier(int $priceModifier): self
    {
        $this->priceModifier = $priceModifier;

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

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }
}
