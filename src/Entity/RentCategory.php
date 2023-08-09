<?php

namespace App\Entity;

use App\Repository\RentCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RentCategoryRepository::class)]
class RentCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'rentCategory', targetEntity: RentCar::class)]
    private Collection $rentCar;

    public function __construct()
    {
        $this->rentCar = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->getname();
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    /**
     * @return Collection<int, RentCar>
     */
    public function getRentCar(): Collection
    {
        return $this->rentCar;
    }

    public function addRentCar(RentCar $rentCar): self
    {
        if (!$this->rentCar->contains($rentCar)) {
            $this->rentCar->add($rentCar);
            $rentCar->setRentCategory($this);
        }

        return $this;
    }

    public function removeRentCar(RentCar $rentCar): self
    {
        if ($this->rentCar->removeElement($rentCar)) {
            // set the owning side to null (unless already changed)
            if ($rentCar->getRentCategory() === $this) {
                $rentCar->setRentCategory(null);
            }
        }

        return $this;
    }
}
