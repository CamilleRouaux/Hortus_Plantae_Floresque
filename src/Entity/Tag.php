<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 */
class Tag
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
     * @ORM\ManyToMany(targetEntity=Plant::class, inversedBy="tags")
     */
    private $plant;

    /**
     * @ORM\ManyToMany(targetEntity=Article::class, inversedBy="tags")
     */
    private $article;

    public function __construct()
    {
        $this->plant = new ArrayCollection();
        $this->article = new ArrayCollection();
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
        }

        return $this;
    }

    public function removePlant(plant $plant): self
    {
        $this->plant->removeElement($plant);

        return $this;
    }

    /**
     * @return Collection<int, article>
     */
    public function getArticle(): Collection
    {
        return $this->article;
    }

    public function addArticle(article $article): self
    {
        if (!$this->article->contains($article)) {
            $this->article[] = $article;
        }

        return $this;
    }

    public function removeArticle(article $article): self
    {
        $this->article->removeElement($article);

        return $this;
    }

    public function __toString() {
        return $this->name;
    }

}
