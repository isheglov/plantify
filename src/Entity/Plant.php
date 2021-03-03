<?php

namespace App\Entity;

use App\Repository\PlantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Planting::class, mappedBy="plant", orphanRemoval=true)
     */
    private $plantings;

    /**
     * @ORM\ManyToMany(targetEntity=Plant::class, inversedBy="companionTo")
     */
    private $companion;

    /**
     * @ORM\ManyToMany(targetEntity=Plant::class, mappedBy="companion")
     */
    private $companionTo;

    public function __construct()
    {
        $this->plantings = new ArrayCollection();
        $this->companion = new ArrayCollection();
        $this->companionTo = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Planting[]
     */
    public function getPlantings(): Collection
    {
        return $this->plantings;
    }

    public function addPlanting(Planting $planting): self
    {
        if (!$this->plantings->contains($planting)) {
            $this->plantings[] = $planting;
            $planting->setPlant($this);
        }

        return $this;
    }

    public function removePlanting(Planting $planting): self
    {
        if ($this->plantings->removeElement($planting)) {
            // set the owning side to null (unless already changed)
            if ($planting->getPlant() === $this) {
                $planting->setPlant(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @return Collection|self[]
     */
    public function getCompanion(): Collection
    {
        return $this->companion;
    }

    public function addCompanion(self $companion): self
    {
        if (!$this->companion->contains($companion)) {
            $this->companion[] = $companion;
        }

        return $this;
    }

    public function removeCompanion(self $companion): self
    {
        $this->companion->removeElement($companion);

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getCompanionTo(): Collection
    {
        return $this->companionTo;
    }

    public function addCompanionTo(self $companionTo): self
    {
        if (!$this->companionTo->contains($companionTo)) {
            $this->companionTo[] = $companionTo;
            $companionTo->addCompanion($this);
        }

        return $this;
    }

    public function removeCompanionTo(self $companionTo): self
    {
        if ($this->companionTo->removeElement($companionTo)) {
            $companionTo->removeCompanion($this);
        }

        return $this;
    }
}
