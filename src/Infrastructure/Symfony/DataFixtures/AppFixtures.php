<?php

namespace Infrastructure\Symfony\DataFixtures;

use Infrastructure\Symfony\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Infrastructure\Symfony\Repository\UserRepository;

class AppFixtures extends Fixture
{
    public function __construct(private UserRepository $userRepository)
    {

    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('admin');
        $user->setPassword('123456');
        $user->setRoles([$role ?? 'ROLE_USER','ROLE_ADMIN']);
        $this->userRepository->add($user);

        $user = new User();
        $user->setUsername('user');
        $user->setPassword('123456');
        $user->setRoles([$role ?? 'ROLE_USER']);
        $this->userRepository->add($user, true);
    }
}
