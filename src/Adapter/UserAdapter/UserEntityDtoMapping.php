<?php

namespace Adapter\UserAdapter;

use Domain\UserDomain\UserDto;
use Infrastructure\Symfony\Entity\User;

class UserEntityDtoMapping
{

    public function ToDto(User $user): UserDto
    {
        return new UserDto($user);
    }

    public function ToUserEntity(UserDto $userDto, User $userEntity): User
    {
        $userEntity->setUsername($userDto->getUsername());
        $userEntity->setPassword($userDto->getPassword());
        $userEntity->setRoles($userDto->getRoles());
        return $userEntity;
    }
}