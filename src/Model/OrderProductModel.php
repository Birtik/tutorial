<?php declare(strict_types=1);

namespace App\Model;

class OrderProductModel
{
    private string $productName;
    private int $amount;
    private int $price;

    public static function create(string $productName, int $amount, int $price): OrderProductModel
    {
        $obj = new self();
        $obj->productName = $productName;
        $obj->amount = $amount;
        $obj->price = $price;

        return $obj;
    }

    /**
     * @return string
     */
    public function getProductName(): string
    {
        return $this->productName;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }
}