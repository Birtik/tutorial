<?php

namespace App\Repository;

use App\Entity\Agreement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Agreement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Agreement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Agreement[]    findAll()
 * @method Agreement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgreementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Agreement::class);
    }
}
