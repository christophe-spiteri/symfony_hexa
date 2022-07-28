<?php

namespace Domain\UserDomain\Validator;

use Infrastructure\Symfony\Entity\User;
use Domain\UserDomain\Port\UserInterface;
use Domain\UserDomain\Exception\ExceptionUser;

class ValidatorUser
{
    private User $user;

    public function __construct(private UserInterface $repositoryUser)
    {
        return $this;
    }

    /**
     * @param User $user
     *
     * @return bool|ExceptionUser
     */
    public function valide(User $user):bool|ExceptionUser
    {
        $this->user = $user;
        $this->isEmpty();
        $this->isUniqueUserName();
        $this->lenPassWord();
        $this->lenUserName();
        return true;
    }

    private function isEmpty()
    {
        if ('' == trim($this->user->getUsername())) {
            throw(new ExceptionUser('username', 'Le nom ne doit pas être vide'));
        }
    }

    private function isUniqueUserName()
    {
        if ($this->repositoryUser->isUniqueUserName($this->user->getUsername())) {
            throw(new ExceptionUser('username', 'Nom déjà utilisé, en choisir un autre'));
        }
    }
    private function lenUserName()
    {
        if (strlen($this->user->getUsername())<3 || strlen($this->user->getUsername())>30) {
            throw(new ExceptionUser('username',sprintf('Le nom d\'utilisateur doit avoir de 3 à 30 caractères maxi et pas %s',strlen($this->user->getUsername()))));
        }
    }
    private function lenPassWord()
    {
        if (strlen($this->user->getPassword())<6 || strlen($this->user->getPassword())>100) {
            throw(new ExceptionUser('password',sprintf('Le mot de passe doit avoir entre 6 et 100 caractères et pas %s',strlen($this->user->getPassword()))));
        }
    }
}