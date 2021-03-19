<?php declare(strict_types=1);

namespace App\Factory;

use App\Entity\Basket;
use App\Entity\User;

class BasketFactory
{
    public function create(User $user): Basket
    {
        return Basket::create($user);
    }
}