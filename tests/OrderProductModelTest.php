<?php declare(strict_types=1);

namespace App\Tests;

use App\Model\OrderProductModel;
use PHPUnit\Framework\TestCase;

class OrderProductModelTest extends TestCase
{
    public function testFirstCreateOrderProductModel()
    {
        $orderProductModel = OrderProductModel::create('Test product',12,150);

        self::assertInstanceOf(OrderProductModel::class,$orderProductModel);
        self::assertEquals('Test product',$orderProductModel->getProductName());
        self::assertEquals(12,$orderProductModel->getAmount());
        self::assertEquals(150,$orderProductModel->getPrice());
    }
}