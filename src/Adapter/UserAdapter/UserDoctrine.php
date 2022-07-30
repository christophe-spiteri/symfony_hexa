<?php

namespace Adapter\UserAdapter;

use Domain\UserDomain\UserDto;
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
        private UserPasswordHasherInterface $passwordHasher,
        private UserEntityDtoMapping        $userEntityDtoMapping
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

    public function addUser(UserDto $user, $flush): UserDto
    {
        $userEntity = $this->userEntityDtoMapping->ToUserEntity($user, new User());
        $this->userRepository->add($userEntity, $flush);
        $user->setId($userEntity->getId());
        return $user;
    }

    public function updateUser(UserDto $user, $flush): UserDto
    {
        $userEntity = $this->userEntityDtoMapping->ToUserEntity($user, $this->findUserById($user->getId()));
        $this->userRepository->update($userEntity, $flush);
        $user->setId($userEntity->getId());
        return $user;
    }

    public function findUserById($id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function isUniqueUserName(UserDto $user): bool
    {
        $userEntity = $this->userRepository->findBy(['username' => $user->getUsername()]);
        if (count($userEntity) == 0) {
            return true;
        }
        if ($userEntity[0]->getId() != $user->getId()) {
            return true;
        }
        return false;
    }

}