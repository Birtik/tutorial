<?php declare(strict_types=1);

namespace App\Factory;

use App\Model\OrderProductModel;

class OrderProductFactory
{
    public function create(string $productName, int $amount, int $price): OrderProductModel
    {
        return OrderProductModel::create($productName,$amount,$price);
    }
}