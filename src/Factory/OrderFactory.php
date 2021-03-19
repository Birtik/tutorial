<?php declare(strict_types=1);

namespace App\Factory;

use App\Entity\Order;
use App\Entity\User;

class OrderFactory
{
    public function create(User $user, array $items): Order
    {
        return Order::create($user,$items);
    }
}