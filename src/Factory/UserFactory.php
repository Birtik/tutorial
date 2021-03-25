<?php declare(strict_types=1);


namespace App\Factory;

use App\Entity\User;
use App\Model\RegisterUserModel;

class UserFactory
{
    public function createUserFromRegisterUserModel(RegisterUserModel $registerUserModel): User
    {
        $userMail = $registerUserModel->getEmail();
        $userPassword = $registerUserModel->getPassword();
        $userFirstName = $registerUserModel->getFirstName();
        $userLastName = $registerUserModel->getLastName();

        return User::create($userMail, $userPassword, $userFirstName, $userLastName);
    }
}