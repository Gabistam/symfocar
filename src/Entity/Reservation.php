<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ["groups" => ["reservation:read"]],
    denormalizationContext: ["groups" => ["reservation:write"]]
)]
#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["reservation:read"])]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["reservation:read", "reservation:write"])]
    private ?RentCar $rentCar = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["reservation:read", "reservation:write"])]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["reservation:read", "reservation:write"])]
    private ?\DateTimeInterface $endDate = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRentCar(): ?RentCar
    {
        return $this->rentCar;
    }

    public function setRentCar(RentCar $rentCar): self
    {
        $this->rentCar = $rentCar;

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

}
