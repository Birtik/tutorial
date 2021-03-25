<?php declare(strict_types=1);


namespace App\Factory;

use App\Entity\User;

class UserFactory
{
    public function create(string $email, string $password, string $firstName, string $lastName): User
    {
        return User::create($email, $password, $firstName, $lastName);
    }
}