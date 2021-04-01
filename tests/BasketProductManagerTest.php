<?php declare(strict_types=1);

namespace App\Tests;

use App\Entity\Basket;
use App\Entity\BasketProduct;
use App\Entity\Product;
use App\Entity\User;
use App\Factory\BasketFactory;
use App\Factory\BasketProductFactory;
use App\Repository\BasketProductRepository;
use App\Repository\BasketRepository;
use App\Repository\ProductRepository;
use App\Service\BasketManager;
use App\Service\BasketProductManager;
use App\Service\ProductManager;
use PHPUnit\Framework\TestCase;

/**s
 * @group time-sensitive
 */
class BasketProductManagerTest extends TestCase
{
    public function testAddProductOnNotExistBasketProduct(): void
    {
        $basketRepositoryMock = $this->getMockBuilder(BasketRepository::class)->disableOriginalConstructor()->getMock();
        $basketProductRepositoryMock = $this->getMockBuilder(
            BasketProductRepository::class
        )->disableOriginalConstructor()->getMock();
        $productRepositoryMock = $this->getMockBuilder(ProductRepository::class)->disableOriginalConstructor()->getMock(
        );
        $productMock = $this->getMockBuilder(Product::class)->getMock();
        $userMock = $this->getMockBuilder(User::class)->getMock();

        $basketManager = $this->getMockBuilder(BasketManager::class)->
        setConstructorArgs([new BasketFactory(), $basketRepositoryMock])
            ->getMock();

        $productManagerMock = $this->getMockBuilder(ProductManager::class)->setConstructorArgs(
            [$productRepositoryMock]
        )->getMock();

        $basketManager->expects(self::once())
            ->method('getActiveBasket')
            ->willReturn(new Basket());

        $basketProductRepositoryMock->expects(self::once())->method('findOneBy')->willReturn(null);
        $basketProductRepositoryMock->expects(self::once())
            ->method('save');

        $basketProductManagerTest = new BasketProductManager(
            $basketRepositoryMock,
            $basketProductRepositoryMock,
            new BasketProductFactory(),
            $basketManager,
            $productManagerMock
        );
        $basketProductManagerTest->addBasketProduct($userMock, $productMock, 12);
    }

    public function testAddProductOnExistBasketProduct(): void
    {
        $basketRepositoryMock = $this->getMockBuilder(BasketRepository::class)->disableOriginalConstructor()->getMock();
        $basketProductRepositoryMock = $this->getMockBuilder(
            BasketProductRepository::class
        )->disableOriginalConstructor()->getMock();
        $productRepositoryMock = $this->getMockBuilder(ProductRepository::class)->disableOriginalConstructor()->getMock(
        );
        $productMock = $this->getMockBuilder(Product::class)->getMock();
        $userMock = $this->getMockBuilder(User::class)->getMock();

        $basketManager = $this->getMockBuilder(BasketManager::class)->
        setConstructorArgs([new BasketFactory(), $basketRepositoryMock])
            ->getMock();

        $productManagerMock = $this->getMockBuilder(ProductManager::class)->setConstructorArgs(
            [$productRepositoryMock]
        )->getMock();

        $basketProduct = BasketProduct::create(new Basket(), $productMock, 3);
        $basketProductRepositoryMock->expects(self::once())->method('findOneBy')->willReturn($basketProduct);

        $basketProductManagerTest = new BasketProductManager(
            $basketRepositoryMock,
            $basketProductRepositoryMock,
            new BasketProductFactory(),
            $basketManager,
            $productManagerMock
        );
        $basketProductManagerTest->addBasketProduct($userMock, $productMock, 12);
    }
}
