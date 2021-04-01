<?php declare(strict_types=1);

namespace App\Tests;

use App\Entity\Basket;
use App\Entity\BasketProduct;
use App\Entity\Product;
use App\Entity\User;
use App\Factory\BasketProductFactory;
use App\Repository\BasketProductRepository;
use App\Repository\BasketRepository;
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
    /**
     * @var Basket|MockObject
     */
    private MockObject $basketMock;

    public function testAddProductOnNotExistBasketProduct(): void
    {
        $this->productManagerMock->expects(self::once())->method('decreaseProductAmount');
        $this->basketManagerMock->expects(self::once())->method('getActiveBasket');

        $this->basketProductRepositoryMock->expects(self::once())->method('findOneBy')->willReturn(null);
        $this->basketProductRepositoryMock->expects(self::once())->method('save');

        $this->basketProductFactoryMock->expects(self::once())->method('create');

        $basketProductManagerTest = new BasketProductManager(
            $this->basketRepositoryMock,
            $this->basketProductRepositoryMock,
            $this->basketProductFactoryMock,
            $this->basketManagerMock,
            $this->productManagerMock
        );

        $basketProductManagerTest->addBasketProduct($this->userMock, $this->productMock, 12);
    }

    public function testAddProductOnExistBasketProduct(): void
    {
        $basketProduct = BasketProduct::create($this->basketMock, $this->productMock, 2);

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

        $basketProductManagerTest->addBasketProduct($this->userMock, $this->productMock, 3);

        self::assertSame(5, $basketProduct->getAmount());
    }

    protected function setUp(): void
    {
        $this->productMock = $this->createMock(Product::class);

        $this->productManagerMock = $this->createMock(ProductManager::class);

        $this->basketProductFactoryMock = $this->createMock(BasketProductFactory::class);
        $this->basketProductRepositoryMock = $this->createMock(BasketProductRepository::class);

        $this->basketManagerMock = $this->createMock(BasketManager::class);
        $this->basketRepositoryMock = $this->createMock(BasketRepository::class);
        $this->basketMock = $this->createMock(Basket::class);

        $this->userMock = $this->createMock(User::class);
    }
}
