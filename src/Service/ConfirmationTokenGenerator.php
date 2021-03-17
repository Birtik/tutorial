<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Token;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class ConfirmationTokenGenerator
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function generateTokenForUser(User $user): Token
    {
        $tokenHashed = md5(sprintf("{$user->getEmail()}%s", time()));
        $expirationDate = (new DateTime())->modify('+25 hours');

        $token = new Token();
        $token->setValue($tokenHashed);
        $token->setUser($user);
        $token->setType(Token::TYPE_REGISTER);
        $token->setExpiredAt($expirationDate);

        $this->em->persist($token);
        $this->em->flush();

        return $token;
    }
}