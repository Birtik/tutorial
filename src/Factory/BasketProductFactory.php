<?php declare(strict_types=1);

namespace App\Factory;

use App\Entity\Basket;
use App\Entity\BasketProduct;
use App\Entity\Product;

class BasketProductFactory
{
    public function create(Basket $basket, Product $product, int $amount): BasketProduct
    {
        return BasketProduct::create($basket,$product,$amount);
    }
}