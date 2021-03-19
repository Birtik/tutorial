<?php declare(strict_types=1);


namespace App\Model;


class OrderProductModel
{
    public string $productName;

    public int $amount;

    public int $price;


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
     * @param string $productName
     */
    public function setProductName(string $productName): void
    {
        $this->productName = $productName;
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

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice(int $price): void
    {
        $this->price = $price;
    }
}