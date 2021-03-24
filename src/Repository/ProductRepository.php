<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    /**
     * ProductRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param $id
     * @return Product|null
     * @throws NonUniqueResultException
     */
    public function findWithCategory($id): ?Product
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
    public function findAllWithCategory(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p','c')
            ->join('p.category', 'c')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param Product $product
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Product $product): void
    {
        $this->_em->persist($product);
        $this->_em->flush();
    }
}
