<?php declare(strict_types=1);

namespace App\Tests;

use App\Entity\Basket;
use App\Entity\Product;
use App\Entity\User;
use App\Factory\BasketFactory;
use App\Factory\BasketProductFactory;
use App\Repository\BasketProductRepository;
use App\Repository\BasketRepository;
use App\Repository\ProductRepository;
use App\Service\BasketProductManager;
use PHPUnit\Framework\TestCase;

/**s
 * @group time-sensitive
 */
class BasketProductManagerTest extends TestCase
{
    public function testAddProductOnNotExistBasket(): void
    {
        $basketRepositoryMock = $this->getMockBuilder(BasketRepository::class)->disableOriginalConstructor()->getMock();
        $basketProductRepositoryMock = $this->getMockBuilder(
            BasketProductRepository::class
        )->disableOriginalConstructor()->getMock();
        $productRepositoryMock = $this->getMockBuilder(ProductRepository::class)->disableOriginalConstructor()->getMock(
        );
        $productMock = $this->getMockBuilder(Product::class)->getMock();
        $userMock = $this->getMockBuilder(User::class)->getMock();

        $productMock->expects(self::once())
            ->method('getAmount')
            ->willReturn(1);

        $basketRepositoryMock->expects(self::once())
            ->method('findActiveUserBasket')
            ->willReturn(null);

        $productRepositoryMock->expects(self::once())
            ->method('save');

        $basketProductRepositoryMock->expects(self::once())
            ->method('save');

        $basketRepositoryMock->expects(self::once())
            ->method('save');

        $basketProductManagerTest = new BasketProductManager(
            $basketRepositoryMock,
            $basketProductRepositoryMock,
            new BasketProductFactory(),
            new BasketFactory(),
            $productRepositoryMock
        );
        $basketProductManagerTest->addBasketProduct($userMock, $productMock, '12');
    }

    public function testAddProductOnExistBasket(): void
    {
        $basketRepositoryMock = $this->getMockBuilder(BasketRepository::class)->disableOriginalConstructor()->getMock();
        $basketProductRepositoryMock = $this->getMockBuilder(
            BasketProductRepository::class
        )->disableOriginalConstructor()->getMock();
        $productRepositoryMock = $this->getMockBuilder(ProductRepository::class)->disableOriginalConstructor()->getMock(
        );
        $productMock = $this->getMockBuilder(Product::class)->getMock();
        $userMock = $this->getMockBuilder(User::class)->getMock();

        $productMock->expects(self::once())
            ->method('getAmount')
            ->willReturn(1);

        $basketRepositoryMock->expects(self::once())
            ->method('findActiveUserBasket')
            ->willReturn(new Basket());

        $productRepositoryMock->expects(self::once())
            ->method('save');

        $basketProductRepositoryMock->expects(self::once())
            ->method('save');

        $basketRepositoryMock->expects(self::never())
            ->method('save');

        $basketProductManagerTest = new BasketProductManager(
            $basketRepositoryMock,
            $basketProductRepositoryMock,
            new BasketProductFactory(),
            new BasketFactory(),
            $productRepositoryMock
        );
        $basketProductManagerTest->addBasketProduct($userMock, $productMock, '12');
    }
}
