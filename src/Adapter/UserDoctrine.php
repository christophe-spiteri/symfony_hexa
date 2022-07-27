<?php

namespace Adapter;

use Infrastructure\Symfony\Entity\User;
use Domain\UserDomain\Port\UserInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use Infrastructure\Symfony\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface as UserInterfaceSymfony;

class UserDoctrine extends UserRepository implements UserInterface
{
    public function __construct(
        private UserRepository              $userRepository,
        private Security                    $security,
        private ManagerRegistry             $registry,
        private UserPasswordHasherInterface $passwordHasher
    )
    {
        parent::__construct($registry, $passwordHasher);
    }

    public function getUserConnected(): ?UserInterfaceSymfony
    {
        return $this->security->getUser();
    }

    public function listUser(): ?array
    {
        return $this->userRepository->findAll();
    }

    public function addUser($user, $flush): void
    {
        $this->userRepository->add($user, $flush);
    }

    public function findUserById($id): ?User
    {
        return $this->userRepository->find($id);
    }
}