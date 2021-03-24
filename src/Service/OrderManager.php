<?php declare(strict_types=1);


namespace App\Service;


use App\Entity\User;
use App\Factory\OrderFactory;
use App\Repository\BasketProductRepository;
use App\Repository\BasketRepository;
use App\Repository\OrderRepository;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class OrderManager
{
    /**
     * @var BasketProductRepository
     */
    private BasketProductRepository $basketProductRepository;

    /**
     * @var OrderFactory
     */
    private OrderFactory $orderFactory;

    /**
     * @var OrderRepository
     */
    private OrderRepository $orderRepository;

    /**
     * @var BasketRepository
     */
    private BasketRepository $basketRepository;

    /**
     * OrderManager constructor.
     * @param BasketProductRepository $basketProductRepository
     * @param BasketRepository $basketRepository
     * @param OrderRepository $orderRepository
     * @param OrderFactory $orderFactory
     */
    public function __construct(
        BasketProductRepository $basketProductRepository,
        BasketRepository $basketRepository,
        OrderRepository $orderRepository,
        OrderFactory $orderFactory
    ) {
        $this->basketProductRepository = $basketProductRepository;
        $this->orderRepository = $orderRepository;
        $this->basketRepository = $basketRepository;
        $this->orderFactory = $orderFactory;
    }

    /**
     * @param User $user
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createOrder(User $user): void
    {
        $basketProducts = $this->basketProductRepository->findAllBasketProductsForUser($user);

        $orderProducts = [];
        foreach ($basketProducts as $item) {
            $product = $item->getProduct();
            $orderProducts[] = [
                'productName' => $product->getName(),
                'amount' => $product->getAmount(),
                'price' => $product->getPrice() * 100,
            ];
        }
        $order = $this->orderFactory->create($user, $orderProducts);
        $this->orderRepository->save($order);
    }

    /**
     * @param User $user
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws NonUniqueResultException
     */
    public function clearUserBasket(User $user): void
    {
        $basket = $this->basketRepository->findBasketWithProductsForUser($user);

        $basket->setDeletedAt(new DateTime());
        $basketProducts = $basket->getBasketProducts();
        foreach ($basketProducts as $basketProduct) {
            $this->basketProductRepository->delete($basketProduct);
        }
    }
}