<?php

namespace App\Entity;

use App\Repository\SoilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SoilRepository::class)
 */
class Soil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Plant::class, mappedBy="soil")
     */
    private $plant;

    public function __construct($type)
    {
        $this->plant = new ArrayCollection();
        $this->type = $type;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, plant>
     */
    public function getPlant(): Collection
    {
        return $this->plant;
    }

    public function addPlant(plant $plant): self
    {
        if (!$this->plant->contains($plant)) {
            $this->plant[] = $plant;
            $plant->setSoil($this);
        }

        return $this;
    }

    public function removePlant(plant $plant): self
    {
        if ($this->plant->removeElement($plant)) {
            // set the owning side to null (unless already changed)
            if ($plant->getSoil() === $this) {
                $plant->setSoil(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->type;
    }

}
