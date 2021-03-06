<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Basket;
use App\Entity\BasketProduct;
use App\Entity\Product;
use App\Entity\User;
use App\Factory\BasketProductFactory;
use App\Model\BasketProductModel;
use App\Repository\BasketProductRepository;
use App\Repository\BasketRepository;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * @var SerializerManager
     */
    public SerializerManager $serializerManager;

    public function __construct(
        BasketRepository $basketRepository,
        BasketProductRepository $basketProductRepository,
        BasketProductFactory $basketProductFactory,
        BasketManager $basketManager,
        ProductManager $productManager,
        SerializerManager $serializerManager
    ) {
        $this->basketRepository = $basketRepository;
        $this->basketProductRepository = $basketProductRepository;
        $this->basketProductFactory = $basketProductFactory;
        $this->basketManager = $basketManager;
        $this->productManager = $productManager;
        $this->serializerManager = $serializerManager;
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
     * @param Product $product
     * @return BasketProduct
     */
    private function getBasketProduct(Product $product): ?BasketProduct
    {
        return $this->basketProductRepository->findOneBy(['product' => $product]);
    }

    /**
     * @param Basket $basket
     * @param Product $product
     * @param int $count
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function createBasketProduct(Basket $basket, Product $product, int $count): void
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
    private function updateBasketProduct(BasketProduct $basketProduct, int $count): void
    {
        $currentBasketProductAmount = $basketProduct->getAmount();
        $basketProduct->setAmount($currentBasketProductAmount + $count);
        $basketProduct->setUpdatedAt(new DateTime());
        $this->basketProductRepository->save($basketProduct);
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
     * @param User $user
     * @param Request $request
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function addBasketProductFromApi(User $user, Request $request): void
    {
        $content = $request->getContent();
        $model = $this->serializerManager->deserializer($content,BasketProductModel::class);

        $amount = $model->getAmount();
        $productId = $model->getProductId();
        $product = $this->productManager->getProduct($productId);

        $this->addBasketProduct($user, $product, $amount);
    }


}