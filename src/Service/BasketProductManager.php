<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Basket;
use App\Entity\BasketProduct;
use App\Entity\Product;
use App\Entity\User;
use App\Factory\BasketProductFactory;
use App\Repository\BasketProductRepository;
use App\Repository\BasketRepository;
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
     * @var BasketProductFactory
     */
    private BasketProductFactory $basketProductFactory;

    /**
     * @var BasketManager
     */
    private BasketManager $basketManager;

    /**
     * @var ProductManager
     */
    private ProductManager $productManager;

    public function __construct(
        BasketRepository $basketRepository,
        BasketProductRepository $basketProductRepository,
        BasketProductFactory $basketProductFactory,
        BasketManager $basketManager,
        ProductManager $productManager
    ) {
        $this->basketRepository = $basketRepository;
        $this->basketProductRepository = $basketProductRepository;
        $this->basketProductFactory = $basketProductFactory;
        $this->basketManager = $basketManager;
        $this->productManager = $productManager;
    }

    /**
     * @param User $user
     * @param Product $product
     * @param int $amount
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function addBasketProduct(User $user, Product $product, int $amount): void
    {
        $basket = $this->basketManager->getActiveBasket($user);
        $this->productManager->decreaseProductAmount($product, $amount);
        $basketProduct = $this->getBasketProduct($product);

        if (null === $basketProduct) {
            $this->createBasketProduct($basket, $product, $amount);

            return;
        }

        $this->updateBasketProduct($basketProduct, $amount);
    }

    /**
     * @param Basket $basket
     * @param Product $product
     * @param int $count
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createBasketProduct(Basket $basket, Product $product, int $count): void
    {
        $basketProduct = $this->basketProductFactory->create($basket, $product, $count);
        $this->basketProductRepository->save($basketProduct);
    }

    /**
     * @param BasketProduct $basketProduct
     * @param int $count
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function updateBasketProduct(BasketProduct $basketProduct, int $count): void
    {
        $currentBasketProductAmount = $basketProduct->getAmount();
        $basketProduct->setAmount($currentBasketProductAmount + $count);
        $basketProduct->setUpdatedAt(new \DateTime());
        $this->basketProductRepository->save($basketProduct);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function clearAllUnusedBasket(): void
    {
        $unacceptableDateTime = new \DateTime();
        $unacceptableDateTime->modify('-48 hours');
        $baskets = $this->basketRepository->findAllUnusedBasket($unacceptableDateTime);

        foreach ($baskets as $basket) {
            $basket->setDeletedAt(new \DateTime());
            $this->restoreAllProductInBasket($basket);
        }
    }

    /**
     * @param Basket $basket
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function restoreAllProductInBasket(Basket $basket): void
    {
        $basketProducts = $basket->getBasketProducts();

        foreach ($basketProducts as $basketProduct) {
            $product = $basketProduct->getProduct();
            $basketProductAmount = $basketProduct->getAmount();
            $this->productManager->increaseProductAmount($product, $basketProductAmount);
            $this->basketProductRepository->delete($basketProduct);
        }
    }

    /**
     * @param Product $product
     * @return BasketProduct
     */
    public function getBasketProduct(Product $product): ?BasketProduct
    {
        return $this->basketProductRepository->findOneBy(['product' => $product]);
    }

}