<?php

namespace App\Repository;

use App\Entity\Basket;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Basket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Basket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Basket[]    findAll()
 * @method Basket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BasketRepository extends ServiceEntityRepository
{
    /**
     * BasketRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Basket::class);
    }

    /**
     * @param User $user
     * @return Basket|null
     * @throws NonUniqueResultException
     */
    public function findActiveUserBasket(User $user): ?Basket
    {
        return $this->createQueryBuilder('b')
            ->select('b')
            ->where('b.user = :user')
            ->andWhere('b.deletedAt is null')
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param User $user
     * @return Basket
     * @throws NonUniqueResultException
     */
    public function findBasketWithProductsForUser(User $user): ?Basket
    {
        return $this->createQueryBuilder('b')
            ->select('b', 'u', 'bp')
            ->join('b.user', 'u')
            ->join('b.basketProducts', 'bp')
            ->where('b.user = :user')
            ->andWhere('b.deletedAt is null')
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param Basket $basket
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Basket $basket): void
    {
        $this->_em->persist($basket);
        $this->_em->flush();
    }

    /**
     * @param DateTime $dateTime
     * @return array
     */
    public function findAllUnusedBasket(DateTime $dateTime): array
    {
        return $this->createQueryBuilder('b')
            ->select('b','bp')
            ->join('b.basketProducts','bp')
            ->where('b.createdAt <= :date')
            ->setParameter('date',$dateTime)
            ->getQuery()
            ->getResult();
    }
}
