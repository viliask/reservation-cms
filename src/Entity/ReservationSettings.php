<?php

namespace App\Entity;

use App\Repository\ReservationSettingsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationSettingsRepository::class)
 */
class ReservationSettings
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $winterStart;

    /**
     * @ORM\Column(type="datetime")
     */
    private $winterEnd;

    /**
     * @ORM\Column(type="smallint")
     */
    private $priceModifier;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWinterStart(): ?\DateTimeInterface
    {
        return $this->winterStart;
    }

    public function setWinterStart(\DateTimeInterface $winterStart): self
    {
        $this->winterStart = $winterStart;

        return $this;
    }

    public function getWinterEnd(): ?\DateTimeInterface
    {
        return $this->winterEnd;
    }

    public function setWinterEnd(\DateTimeInterface $winterEnd): self
    {
        $this->winterEnd = $winterEnd;

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
}
