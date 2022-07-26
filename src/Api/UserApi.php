<?php

namespace Api;

use Domain\UserDomain\Port\UserInterface;

class UserApi
{
    public function __construct(Private UserInterface $userInterface,)
    {
        
    }
    public function getListeUser()
    {
        return $this->userInterface->listUser();
    }
}