<?php

namespace App\Repository;

use App\Entity\BasketProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BasketProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method BasketProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method BasketProduct[]    findAll()
 * @method BasketProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BasketProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BasketProduct::class);
    }

    public function findAllProductsForUser($username)
    {
        return $this->createQueryBuilder('i')
            ->select('i', 'b', 'u','p')
            ->join('i.basket', 'b')
            ->join('i.product','p')
            ->join('b.user', 'u')
            ->where('u.email = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getResult();
    }
}
