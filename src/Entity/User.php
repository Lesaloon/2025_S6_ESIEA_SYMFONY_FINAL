<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
	#[Groups(['gear:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
	#[Groups(['gear:read'])]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: false)]
	// #[Groups([])]
    private ?string $password = null;

    #[ORM\Column(type: 'json')]
	// #[Groups([])]
    private array $roles = [];

    #[ORM\Column]
	// #[Groups([])]
    private ?bool $isApproved = false;

    #[ORM\Column]
	// #[Groups([])]
    private ?bool $isLocked = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // garantit au moins le rôle USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function isApproved(): ?bool
    {
        return $this->isApproved;
    }

    public function setIsApproved(bool $isApproved): static
    {
        $this->isApproved = $isApproved;
        return $this;
    }

    public function isLocked(): ?bool
    {
        return $this->isLocked;
    }

    public function setIsLocked(bool $isLocked): static
    {
        $this->isLocked = $isLocked;
        return $this;
    }

    // Requis par Symfony Security
    public function getUserIdentifier(): string
    {
        return $this->email ?? '';
    }

    // Méthode obligatoire même si vide
    public function eraseCredentials(): void
    {

    }
}
