<?php

namespace App\Entity;

use App\Repository\ExposureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExposureRepository::class)
 */
class Exposure
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Plant::class, mappedBy="exposure")
     */
    private $plant;

    public function __construct($name)
    {
        $this->plant = new ArrayCollection();
        $this->name = $name;
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
            $plant->setExposure($this);
        }

        return $this;
    }

    public function removePlant(plant $plant): self
    {
        if ($this->plant->removeElement($plant)) {
            // set the owning side to null (unless already changed)
            if ($plant->getExposure() === $this) {
                $plant->setExposure(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->name;
    }

}
