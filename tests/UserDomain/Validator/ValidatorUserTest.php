<?php

namespace App\Tests\UserDomain\Validator;

use Infrastructure\Symfony\Entity\User;
use Domain\UserDomain\Exception\ExceptionUser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ValidatorUserTest extends WebTestCase
{
    private $validator;
    private $user;
    private $apiUser;

    protected function setUp(): void
    {
        $this->validator = (static::getContainer()->get("Domain\UserDomain\Validator\ValidatorUser"));
        $this->apiUser   = (static::getContainer()->get("Api\UserApi"));
        $this->user      = new User();
    }

    public function test_isEmpty_Username()
    {
        $this->expectException(ExceptionUser::class);
        $this->expectExceptionMessage('Le nom ne doit pas être vide');
        $this->user->setUsername('');
        $this->validator->valide($this->user);
    }

    public function test_isUnique_Username()
    {
        $this->expectException(ExceptionUser::class);
        $this->expectExceptionMessage('Nom déjà utilisé, en choisir un autre');

        $fixture = new User();
        $aleatoire = (string)time();

        $fixture->setUsername('UserDejaPresent'.$aleatoire);
        $fixture->setRoles(['ROLE_USER']);
        $fixture->setPassword('FakePassword');
        $this->apiUser->addUser($fixture, true);
        $fixture->setUsername('UserDejaPresent'.$aleatoire);
        $this->apiUser->addUser($fixture, true);
    }

    public function test_len_Username_Trop_Petit()
    {
        $this->expectException(ExceptionUser::class);
        $this->expectExceptionMessage("Le nom d'utilisateur doit avoir de 3 à 30 caractères maxi et pas 1");
        $this->user->setUsername('q');
        $this->user->setRoles(['ROLE_USER']);
        $this->user->setPassword('FakePassword');
        $this->validator->valide($this->user);
    }
    public function test_len_Username_Trop_Grand()
    {
        $this->expectException(ExceptionUser::class);
        $this->expectExceptionMessage("Le nom d'utilisateur doit avoir de 3 à 30 caractères maxi et pas 31");
        $this->user->setUsername(str_pad('username', 31, "x", STR_PAD_RIGHT));
        $this->user->setRoles(['ROLE_USER']);
        $this->user->setPassword('FakePassword');
        $this->validator->valide($this->user);
    }
    public function test_len_Password_Trop_Petit()
    {
        $this->expectException(ExceptionUser::class);
        $this->expectExceptionMessage("Le mot de passe doit avoir entre 6 et 100 caractères et pas 5");
        $this->user->setUsername('fakeUserName');
        $this->user->setRoles(['ROLE_USER']);
        //dd(str_pad('p', 5, "x", STR_PAD_RIGHT));
        $this->user->setPassword('eeeee');
        $this->validator->valide($this->user);
    }
    public function test_len_Password_Trop_Grand()
    {
        $this->expectException(ExceptionUser::class);
        $this->expectExceptionMessage("Le mot de passe doit avoir entre 6 et 100 caractères et pas 101");
        $this->user->setUsername('fakeUserName');
        $this->user->setRoles(['ROLE_USER']);
        $this->user->setPassword(str_pad('p', 101, "x", STR_PAD_RIGHT));
        $this->validator->valide($this->user);
    }
    public function test_len_UserName_and_Password_ok()
    {
        $this->user->setUsername('fakeName');
        $this->user->setRoles(['ROLE_USER']);
        $this->user->setPassword('123456789');
        $this->validator->valide($this->user);
        $this->assertTrue(true);
    }

}
