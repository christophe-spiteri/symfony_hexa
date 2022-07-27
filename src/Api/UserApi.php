<?php

namespace Api;

use Infrastructure\Symfony\Entity\User;
use Domain\UserDomain\Port\UserInterface;

class UserApi
{
    public function __construct(private UserInterface $userInterface)
    {

    }

    public function getListeUser()
    {
        return $this->userInterface->listUser();
    }

    public function addUser(User $user, $flush = false)
    {
        $this->userInterface->addUser($user, $flush);
    }

    public function findUserById($id): ?User
    {
        return $this->userInterface->findUserById($id);
    }
}