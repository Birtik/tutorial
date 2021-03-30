<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Basket;
use App\Entity\BasketProduct;
use App\Entity\Product;
use App\Entity\User;
use App\Factory\BasketFactory;
use App\Factory\BasketProductFactory;
use App\Repository\BasketProductRepository;
use App\Repository\BasketRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class BasketProductManager
{

    private const SUBSTRACTION_ACTION = 1;

    private const ADDITION_ACTION = 2;


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

        $basketProduct = $this->basketProductRepository->findOneBy(['product' => $product]);
        if (null === $basketProduct) {
            $this->createBasketProduct($basket, $product, $amount);
        } else {
            $this->updateBasketProduct($basketProduct, $amount);
        }

        $this->productManager->updateProductAmount($product, $amount, self::SUBSTRACTION_ACTION);
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
            $this->productManager->updateProductAmount($product,$basketProductAmount,self::ADDITION_ACTION);
            $this->basketProductRepository->delete($basketProduct);
        }
    }
}