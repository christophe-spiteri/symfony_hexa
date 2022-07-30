<?php

namespace Domain\UserDomain\Api;

use Domain\UserDomain\UserDto;
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

    public function addUser(UserDto $user, $flush = false): ?UserDto
    {
        if ($this->validationUser->valide($user)) {
            return $this->userInterface->addUser($user, $flush);
        }
        return null;
    }

    public function updateUser(UserDto $user, $flush = false): ?UserDto
    {
        if ($this->validationUser->valide($user)) {
            return $this->userInterface->updateUser($user, $flush);
        }
        return null;
    }

    public function findUserById($id): ?User
    {
        return $this->userInterface->findUserById($id);
    }
}