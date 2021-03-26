<?php

namespace App\Repository;

use App\Entity\Token;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Token|null find($id, $lockMode = null, $lockVersion = null)
 * @method Token|null findOneBy(array $criteria, array $orderBy = null)
 * @method Token[]    findAll()
 * @method Token[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TokenRepository extends ServiceEntityRepository
{
    /**
     * TokenRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Token::class);
    }

    /**
     * @param string $token
     * @param string $type
     * @return Token|null
     * @throws NonUniqueResultException
     */
    public function findTokenWithUser(string $token, string $type): ?Token
    {
        return $this->createQueryBuilder('t')
            ->select('t', 'u')
            ->join('t.user', 'u')
            ->where('t.value = :token')
            ->andWhere('t.type = :type')
            ->setParameter('token', $token)
            ->setParameter('type', $type)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
