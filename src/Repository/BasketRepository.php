<?php

namespace App\Repository;

use App\Entity\Basket;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Basket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Basket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Basket[]    findAll()
 * @method Basket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BasketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Basket::class);
    }

    public function findActiveUserBasket(User $user)
    {
        return $this->createQueryBuilder('b')
            ->select('b')
            ->where('b.user = :user')
            ->andWhere('b.deletedAt is null')
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function save(Basket $basket): void
    {
        $this->_em->persist($basket);
        $this->_em->flush();
    }
}
