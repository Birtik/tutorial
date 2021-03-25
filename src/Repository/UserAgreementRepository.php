<?php

namespace App\Repository;

use App\Entity\UserAgreement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserAgreement|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAgreement|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAgreement[]    findAll()
 * @method UserAgreement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserAgreementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAgreement::class);
    }
}
