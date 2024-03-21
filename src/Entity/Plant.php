<?php

namespace App\Entity;

use App\Entity\Favorite;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlantRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=PlantRepository::class)
 */
class Plant
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
    private $latin_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $amount_of_water;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $frequency_of_watering;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pruning;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $origin;

    /**
     * @ORM\ManyToOne(targetEntity=Location::class, inversedBy="plant")
     */
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity=Exposure::class, inversedBy="plant")
     */
    private $exposure;

    /**
     * @ORM\ManyToOne(targetEntity=Soil::class, inversedBy="plant")
     */
    private $soil;

    /**
     * @ORM\OneToMany(targetEntity=Picture::class, mappedBy="plant", orphanRemoval=true)
     */
    private $pictures;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, mappedBy="plant")
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=Favorite::class, mappedBy="plant")
     */
    private $favorite;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->favorite = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLatinName(): ?string
    {
        return $this->latin_name;
    }

    public function setLatinName(string $latin_name): self
    {
        $this->latin_name = $latin_name;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAmountOfWater(): ?int
    {
        return $this->amount_of_water;
    }

    public function setAmountOfWater(?int $amount_of_water): self
    {
        $this->amount_of_water = $amount_of_water;

        return $this;
    }

    public function getFrequencyOfWatering(): ?string
    {
        return $this->frequency_of_watering;
    }

    public function setFrequencyOfWatering(?string $frequency_of_watering): self
    {
        $this->frequency_of_watering = $frequency_of_watering;

        return $this;
    }

    public function getPruning(): ?string
    {
        return $this->pruning;
    }

    public function setPruning(?string $pruning): self
    {
        $this->pruning = $pruning;

        return $this;
    }

    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function setOrigin(?string $origin): self
    {
        $this->origin = $origin;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getExposure(): ?Exposure
    {
        return $this->exposure;
    }

    public function setExposure(?Exposure $exposure): self
    {
        $this->exposure = $exposure;

        return $this;
    }

    public function getSoil(): ?Soil
    {
        return $this->soil;
    }

    public function setSoil(?Soil $soil): self
    {
        $this->soil = $soil;

        return $this;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setPlant($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getPlant() === $this) {
                $picture->setPlant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addPlant($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removePlant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, favorite>
     */
    public function getFavorite(): Collection
    {
        return $this->favorite;
    }

    public function addFavorite(Favorite $favorite): self
    {
        if (!$this->favorite->contains($favorite)) {
            $this->favorite[] = $favorite;
            $favorite->setPlant($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): self
    {
        if ($this->favorite->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getPlant() === $this) {
                $favorite->setPlant(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->name;
    }

}
