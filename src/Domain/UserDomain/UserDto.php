<?php

namespace Domain\UserDomain;

use Infrastructure\Symfony\Entity\User;

class UserDto
{

    private ?int $id = null;

    private ?string $username = null;

    private array $roles = [];

    private ?string $password = null;

    public function __construct(User $user)
    {
        $this->id       = $user->getId();
        $this->username = $user->getUsername();
        $this->password = $user->getPassword();
        $this->roles    = $user->getRoles();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string)$this->username;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }


    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}