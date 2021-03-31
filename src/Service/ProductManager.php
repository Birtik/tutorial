<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityNotFoundException;
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
     * @param int $action
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function updateProductAmount(Product $product, int $amount, int $action): void
    {
        if ($action === 1){
            $product->setAmount($product->getAmount() - $amount);
        }else if ($action === 2){
            $product->setAmount($product->getAmount() + $amount);
        }

        $this->productRepository->save($product);
    }

    /**
     * @param int $id
     * @return Product|null
     * @throws EntityNotFoundException
     */
    public function getProduct(int $id): Product
    {
        $product = $this->productRepository->find($id);

        if (null === $product){
            throw new EntityNotFoundException('Product not found');
        }

        return $product;
    }
}