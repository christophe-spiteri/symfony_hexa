<?php

namespace Api;

use Infrastructure\Symfony\Entity\User;
use Domain\UserDomain\Port\UserInterface;
use Domain\UserDomain\Validator\ValidatorUser;

class UserApi
{
    public function __construct(
        private UserInterface $userInterface,
        private ValidatorUser $validationUser
    )
    {

    }

    public function getListeUser()
    {
        return $this->userInterface->listUser();
    }

    public function addUser(User $user, $flush = false)
    {
        if ($this->validationUser->valide($user)) {
            $this->userInterface->addUser($user, $flush);
        }

    }

    public function findUserById($id): ?User
    {
        return $this->userInterface->findUserById($id);
    }
}