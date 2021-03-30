<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Basket;
use App\Entity\User;
use App\Factory\BasketFactory;
use App\Repository\BasketRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class BasketManager
{
    /**
     * @var BasketFactory
     */
    public BasketFactory $basketFactory;

    /**
     * @var BasketRepository
     */
    public BasketRepository $basketRepository;

    /**
     * BasketManager constructor.
     * @param BasketFactory $basketFactory
     * @param BasketRepository $basketRepository
     */
    public function __construct(BasketFactory $basketFactory, BasketRepository $basketRepository)
    {
        $this->basketFactory = $basketFactory;
        $this->basketRepository = $basketRepository;
    }

    /**
     * @param User $user
     * @return Basket
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function getActiveBasket(User $user): Basket
    {
        $basket = $this->basketRepository->findActiveUserBasket($user);

        if ($basket === null) {
            return $this->createNewBasket($user);
        }

        return $basket;
    }

    /**
     * @param User $user
     * @return Basket
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createNewBasket(User $user): Basket
    {
        $newBasket = $this->basketFactory->create($user);
        $this->basketRepository->save($newBasket);

        return $newBasket;
    }
}