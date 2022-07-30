<?php

namespace Domain\UserDomain\Port;

use Domain\UserDomain\UserDto;
use Infrastructure\Symfony\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface as UserInterfaceSymfony;

interface UserInterface
{
    public function getUserConnected(): ?UserInterfaceSymfony;

    /**
     * @return array<int, object> The objects.
     */
    public function listUser(): ?array;
    public function addUser(UserDto $user, $flush):UserDto;
    public function updateUser(UserDto $user, $flush):UserDto;
    public function findUserById($id):?User;
    public function isUniqueUserName(UserDto $userDto):bool;

}