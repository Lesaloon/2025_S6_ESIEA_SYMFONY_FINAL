<?php

namespace App\Entity;

use App\Repository\GearRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Attribute\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\MaxDepth;


#[ORM\Entity(repositoryClass: GearRepository::class)]
class Gear
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
	#[Groups(['gear:read', 'gear:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
	#[Groups(['gear:read', 'gear:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
	#[Groups(['gear:read', 'gear:write'])]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
	#[Groups(['gear:read', 'gear:write'])]
    private ?string $brand = null;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: false)]
	#[Groups(['gear:read', 'gear:write'])]
    private ?User $user = null;

    /**
     * @var Collection<int, Maintenance>
     */
    #[ORM\OneToMany(targetEntity: Maintenance::class, mappedBy: 'gear', orphanRemoval: true)]
	#[Groups(['gear:read'])]

    private Collection $maintenances;

    public function __construct()
    {
        $this->maintenances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Maintenance>
     */
    public function getMaintenances(): Collection
    {
        return $this->maintenances;
    }

    public function addMaintenance(Maintenance $maintenance): static
    {
        if (!$this->maintenances->contains($maintenance)) {
            $this->maintenances->add($maintenance);
            $maintenance->setGear($this);
        }

        return $this;
    }

    public function removeMaintenance(Maintenance $maintenance): static
    {
        if ($this->maintenances->removeElement($maintenance)) {
            // set the owning side to null (unless already changed)
            if ($maintenance->getGear() === $this) {
                $maintenance->setGear(null);
            }
        }

        return $this;
    }
}
