<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

     /**
      * @return Product[] Returns an array of Product objects
      */
    public function findWithCategory($id)
    {
        return $this->createQueryBuilder('p')
            ->select('p','c','o')
            ->join('p.category', 'c')
            ->join('p.productOpinion','o')
            ->andWhere('p.id = :val')
            ->setParameter('val', $id)
            ->orderBy('o.rate', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function findAllWithCategory()
    {
        return $this->createQueryBuilder('p')
            ->select('p','c')
            ->join('p.category', 'c')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}
