<?php

namespace Adapter;

use Domain\UserDomain\Port\UserInterface;
use Symfony\Component\Security\Core\Security;
use Infrastructure\Symfony\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface as UserInterfaceSymfony;

class UserDoctrine implements UserInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private Security       $security)
    {

    }

    public function getUserConnected(): ?UserInterfaceSymfony
    {
        return $this->security->getUser();
    }

    public function listUser():?array
    {
        return $this->userRepository->findAll();
    }
}