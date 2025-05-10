<?php

namespace App\Entity;

use App\Repository\MaintenanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: MaintenanceRepository::class)]
class Maintenance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
	#[Groups(['maintenance:read', 'maintenance:write', 'gear:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
	#[Groups(['maintenance:read', 'maintenance:write', 'gear:read'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'maintenances')]
    #[ORM\JoinColumn(nullable: false)]
	#[Groups(['maintenance:read'])]
	#[MaxDepth(1)]
    private ?Gear $gear = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
	#[Groups(['maintenance:read', 'maintenance:write', 'gear:read'])]
    private ?\DateTime $date = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
	#[Groups(['maintenance:read', 'maintenance:write', 'gear:read'])]
    private ?string $description = null;

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

    public function getGear(): ?Gear
    {
        return $this->gear;
    }

    public function setGear(?Gear $gear): static
    {
        $this->gear = $gear;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

}
