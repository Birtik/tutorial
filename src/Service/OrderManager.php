<?php declare(strict_types=1);


namespace App\Service;


use App\Entity\User;
use App\Factory\OrderFactory;
use App\Factory\OrderProductFactory;
use App\Repository\BasketProductRepository;
use App\Repository\OrderRepository;

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
     * @var OrderProductFactory
     */
    private OrderProductFactory $orderProductFactory;

    /**
     * @var OrderRepository
     */
    private OrderRepository $orderRepository;

    /**
     * @var SerializerManager
     */
    private SerializerManager $serializerManager;

    /**
     * OrderManager constructor.
     * @param SerializerManager $serializerManager
     * @param BasketProductRepository $basketProductRepository
     * @param OrderRepository $orderRepository
     * @param OrderFactory $orderFactory
     * @param OrderProductFactory $orderProductFactory
     */
    public function __construct(
        SerializerManager $serializerManager,
        BasketProductRepository $basketProductRepository,
        OrderRepository $orderRepository,
        OrderFactory $orderFactory,
        OrderProductFactory $orderProductFactory
    ) {
        $this->basketProductRepository = $basketProductRepository;
        $this->orderRepository = $orderRepository;
        $this->orderProductFactory = $orderProductFactory;
        $this->orderFactory = $orderFactory;
        $this->serializerManager = $serializerManager;
    }

    public function submitOrder(User $user): void
    {
        $basketProducts = $this->basketProductRepository->findAllProductsForUser($user->getUsername());
        $orderProducts = [];

        foreach ($basketProducts as $item) {
            $product = $item->getProduct();
            $orderProduct = $this->orderProductFactory->create(
                $product->getName(),
                $item->getAmount(),
                $product->getPrice() * 100
            );
            $orderProducts[] = $orderProduct;
        }

        $items = $this->serializerManager->serializer($orderProducts);

        $order = $this->orderFactory->create($user, $items);
        $this->orderRepository->save($order);
    }

    public function clearBasket(User $user): void
    {
        $basketProducts = $this->basketProductRepository->findAllProductsForUser($user->getUsername());
        $basket = $basketProducts[0]->getBasket();
        $basket->setDeletedAt(new \DateTime());

        foreach($basketProducts as $basketProduct)
        {
            $this->basketProductRepository->delete($basketProduct);
        }
    }
}