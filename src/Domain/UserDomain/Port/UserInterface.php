<?php

namespace Domain\UserDomain\Port;

use Infrastructure\Symfony\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface as UserInterfaceSymfony;

interface UserInterface
{
    public function getUserConnected(): ?UserInterfaceSymfony;

    /**
     * @return array<int, object> The objects.
     */
    public function listUser(): ?array;
    public function addUser($user, $flush):void;
    public function findUserById($id):?User;

}