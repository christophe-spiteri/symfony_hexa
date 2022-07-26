<?php

namespace Domain\UserDomain\Port;

use Symfony\Component\Security\Core\User\UserInterface as UserInterfaceSymfony;

interface UserInterface
{
    public function getUserConnected(): ?UserInterfaceSymfony;

    /**
     * @return array<int, object> The objects.
     */
    public function listUser(): ?array;
}