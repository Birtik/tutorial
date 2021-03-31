<?php declare(strict_types=1);

namespace App\Model;

class BasketProductModel
{
    /**
     * @var int
     */
    public int $productId;

    /**
     * @var int
     */
    public int $amount;

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     */
    public function setProduct(int $productId): void
    {
        $this->productId = $productId;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }
}