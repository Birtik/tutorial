<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use App\Entity\User;
use App\Factory\BasketFactory;
use App\Factory\BasketProductFactory;
use App\Repository\BasketProductRepository;
use App\Repository\BasketRepository;
use App\Repository\ProductRepository;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

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

    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    public function __construct(
        BasketRepository $basketRepository,
        BasketProductRepository $basketProductRepository,
        BasketProductFactory $basketProductFactory,
        BasketFactory $basketFactory,
        ProductRepository $productRepository
    ) {
        $this->basketRepository = $basketRepository;
        $this->basketProductRepository = $basketProductRepository;
        $this->basketFactory = $basketFactory;
        $this->basketProductFactory = $basketProductFactory;
        $this->productRepository = $productRepository;
    }

    /**
     * @param User $user
     * @param Product $product
     * @param string $count
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function addBasketProduct(User $user, Product $product, string $count): void
    {
        $basket = $this->basketRepository->findActiveUserBasket($user);
        if ($basket === null) {
            $basket = $this->basketFactory->create($user);
            $this->basketRepository->save($basket);
        }
        $basketProduct = $this->basketProductFactory->create($basket, $product, (int)$count);
        $this->basketProductRepository->save($basketProduct);
        $product->setAmount($product->getAmount() - (int)$count);
        $this->productRepository->save($product);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function clearAllUnusedBasket(): void
    {
        $unacceptableDateTime = new DateTime();
        $unacceptableDateTime->modify('-48 hours');
        $baskets = $this->basketRepository->findAllUnusedBasket($unacceptableDateTime);

        foreach ($baskets as $basket) {
            $basket->setDeletedAt(new DateTime());
            $basketProducts = $basket->getBasketProducts();
            foreach ($basketProducts as $basketProduct) {
                $product = $basketProduct->getProduct();
                $basketAmount = $basketProduct->getAmount();
                $productAmount = $product->getAmount();
                $product->setAmount($productAmount+$basketAmount);
                $this->basketProductRepository->delete($basketProduct);
            }
        }
    }
}