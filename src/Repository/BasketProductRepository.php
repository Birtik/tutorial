<?php

namespace App\Repository;

use App\Entity\BasketProduct;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BasketProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method BasketProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method BasketProduct[]    findAll()
 * @method BasketProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BasketProductRepository extends ServiceEntityRepository
{
    /**
     * BasketProductRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BasketProduct::class);
    }

    /**
     * @param User $user
     * @return array
     */
    public function findAllBasketProductsForUser(User $user): array
    {
        return $this->createQueryBuilder('i')
            ->select('i', 'b','p')
            ->join('i.basket', 'b')
            ->join('i.product','p')
            ->where('b.user = :user')
            ->andWhere('b.deletedAt is null')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param BasketProduct $basketProduct
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(BasketProduct $basketProduct): void
    {
        $this->_em->persist($basketProduct);
        $this->_em->flush();
    }

    /**
     * @param BasketProduct $basketProduct
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(BasketProduct $basketProduct): void
    {
        $this->_em->remove($basketProduct);
        $this->_em->flush();
    }
}
