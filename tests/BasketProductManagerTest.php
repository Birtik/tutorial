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
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**s
 * @group time-sensitive
 */
class BasketProductManagerTest extends TestCase
{
    /**
     * @var ProductManager|MockObject
     */
    private MockObject $productManagerMock;
    /**
     * @var BasketProductFactory|MockObject
     */
    private MockObject $basketProductFactoryMock;
    /**
     * @var BasketProductRepository|MockObject
     */
    private MockObject $basketProductRepositoryMock;
    /**
     * @var BasketManager|MockObject
     */
    private MockObject $basketManagerMock;
    /**
     * @var User|MockObject
     */
    private MockObject $userMock;
    /**
     * @var Product|MockObject
     */
    private MockObject $productMock;
    /**
     * @var BasketRepository|MockObject
     */
    private MockObject $basketRepositoryMock;

    protected function setUp(): void
    {
        $this->productMock = $this->createMock(Product::class);
        $this->productManagerMock = $this->createMock(ProductManager::class);

        $this->basketProductFactoryMock = $this->createMock(BasketProductFactory::class);
        $this->basketProductRepositoryMock = $this->createMock(BasketProductRepository::class);

        $this->basketManagerMock = $this->createMock(BasketManager::class);
        $this->basketRepositoryMock = $this->createMock(BasketRepository::class);

        $this->userMock = $this->createMock(User::class);
    }

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
        $basketProduct = $this->createMock(BasketProduct::class);
        $this->productManagerMock->expects(self::once())->method('decreaseProductAmount');
        $this->basketManagerMock->expects(self::once())->method('getActiveBasket');

        $this->basketProductRepositoryMock->expects(self::once())->method('findOneBy')->willReturn($basketProduct);
        $this->basketProductRepositoryMock->expects(self::once())->method('save');

        $this->basketProductFactoryMock->expects(self::never())->method('create');

        $basketProductManagerTest = new BasketProductManager(
            $this->basketRepositoryMock,
            $this->basketProductRepositoryMock,
            $this->basketProductFactoryMock,
            $this->basketManagerMock,
            $this->productManagerMock
        );

        $basketProductManagerTest->addBasketProduct($this->userMock, $this->productMock, 12);
    }
}
