<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LocationRepository::class)
 */
class Location
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
    private $zone;

    /**
     * @ORM\OneToMany(targetEntity=Plant::class, mappedBy="location")
     */
    private $plant;

    public function __construct( $zone )
    {
        $this->plant = new ArrayCollection();
        $this->zone = $zone;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getZone(): ?string
    {
        return $this->zone;
    }

    public function setZone(string $zone): self
    {
        $this->zone = $zone;

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
            $plant->setLocation($this);
        }

        return $this;
    }

    public function removePlant(plant $plant): self
    {
        if ($this->plant->removeElement($plant)) {
            // set the owning side to null (unless already changed)
            if ($plant->getLocation() === $this) {
                $plant->setLocation(null);
            }
        }

        return $this;
    }


    public function __toString() {
        return $this->zone;
    }
}
