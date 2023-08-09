<?php

namespace App\Entity;

use App\Repository\RentCarRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RentCarRepository::class)]
class RentCar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    private ?string $illustration = null;

    #[ORM\Column(length: 255)]
    private ?string $subtitle = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?bool $isDispo = null;

    #[ORM\ManyToOne(inversedBy: 'rentCar')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RentCategory $rentCategory = null;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getIllustration(): ?string
    {
        return $this->illustration;
    }

    public function setIllustration(string $illustration): self
    {
        $this->illustration = $illustration;

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function isIsDispo(): ?bool
    {
        return $this->isDispo;
    }

    public function setIsDispo(bool $isDispo): self
    {
        $this->isDispo = $isDispo;

        return $this;
    }

    public function getRentCategory(): ?RentCategory
    {
        return $this->rentCategory;
    }

    public function setRentCategory(?RentCategory $rentCategory): self
    {
        $this->rentCategory = $rentCategory;

        return $this;
    }
}
