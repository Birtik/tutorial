<?php declare(strict_types=1);

namespace App\Tests;

use App\Model\OrderProductModel;
use PHPUnit\Framework\TestCase;

class OrderProductModelTest extends TestCase
{
    public function testFirstCreateOrderProductModel()
    {
        $orderProductModel = OrderProductModel::create('Test product',12,150);

        self::assertSame('Test product',$orderProductModel->getProductName());
        self::assertSame(12,$orderProductModel->getAmount());
        self::assertSame(150,$orderProductModel->getPrice());
    }
}