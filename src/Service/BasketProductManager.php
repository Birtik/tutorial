<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use App\Entity\User;
use App\Factory\BasketFactory;
use App\Factory\BasketProductFactory;
use App\Repository\BasketProductRepository;
use App\Repository\BasketRepository;

class BasketProductManager
{
    /**
     * @var BasketRepository
     */
    private BasketRepository $basketRepository;

    /**
     * @var BasketProductRepository
     */
    private BasketProductRepository $basketProductRepository;

    public function __construct(BasketRepository $basketRepository, BasketProductRepository $basketProductRepository)
    {
        $this->basketRepository = $basketRepository;
        $this->basketProductRepository = $basketProductRepository;
    }

    public function addProduct(User $user, Product $product, string $count): void
    {
        $basket = $this->basketRepository->findActiveUserBasket($user->getUsername());

        if ($basket === null) {
            $basket = new BasketFactory();
            $basket = $basket->create($user);
            $this->basketRepository->save($basket);
        }

        $basketProduct = new BasketProductFactory();
        $basketProduct = $basketProduct->create($basket,$product,(int)$count);
        $this->basketProductRepository->save($basketProduct);
    }
}