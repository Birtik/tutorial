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
    /**
     * @var BasketFactory
     */
    private BasketFactory $basketFactory;
    /**
     * @var BasketProductFactory
     */
    private BasketProductFactory $basketProductFactory;

    public function __construct(BasketRepository $basketRepository, BasketProductRepository $basketProductRepository, BasketProductFactory $basketProductFactory, BasketFactory $basketFactory)
    {
        $this->basketRepository = $basketRepository;
        $this->basketProductRepository = $basketProductRepository;
        $this->basketFactory = $basketFactory;
        $this->basketProductFactory = $basketProductFactory;
    }

    public function addProduct(User $user, Product $product, string $count): void
    {
        $basket = $this->basketRepository->findActiveUserBasket($user);

        if ($basket === null) {

            $basket = $this->basketFactory->create($user);
            $this->basketRepository->save($basket);
        }

        $basketProduct = $this->basketProductFactory->create($basket,$product,(int)$count);
        $this->basketProductRepository->save($basketProduct);
    }
}