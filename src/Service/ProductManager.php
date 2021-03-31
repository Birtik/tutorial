<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class ProductManager
{
    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    /**
     * ProductManager constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param Product $product
     * @param int $amount
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function increaseProductAmount(Product $product, int $amount): void
    {
        $product->setAmount($product->getAmount() + $amount);
        $this->productRepository->save($product);
    }

    /**
     * @param Product $product
     * @param int $amount
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function decreaseProductAmount(Product $product, int $amount): void
    {
        if ($amount < 1) {
            throw new \InvalidArgumentException(sprintf('Amount value should be positive int. "%s" given.', $amount));
        }

        $product->setAmount($product->getAmount() + (-1) * $amount);
        $this->productRepository->save($product);
    }
}