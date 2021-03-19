<?php declare(strict_types=1);


namespace App\Model;


use Symfony\Component\Validator\Constraints as Assert;

class FormBasketProductModel
{
    
    private int $count;

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount(int $count): void
    {
        $this->count = $count;
    }
}