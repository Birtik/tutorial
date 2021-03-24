<?php declare(strict_types=1);


namespace App\Model;

class FormBasketProductModel
{
    private int $amount;

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $count
     */
    public function setAmount(int $count): void
    {
        $this->amount = $count;
    }
}