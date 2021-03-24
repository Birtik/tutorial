<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Token;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ConfirmationTokenGenerator
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * @var DateTimeProviderInterface
     */
    private DateTimeProviderInterface $dateTimeProvider;


    public function __construct(EntityManagerInterface $em, DateTimeProviderInterface $dateTimeProvider)
    {
        $this->em = $em;
        $this->dateTimeProvider = $dateTimeProvider;
    }

    /**
     * @param User $user
     * @return Token
     */
    public function generateTokenForUser(User $user): Token
    {
        $tokenHashed = md5(sprintf("{$user->getEmail()}%s", $this->dateTimeProvider->getCurrentTime()));
        $expirationDate = ($this->dateTimeProvider->getCurrentDateTime())->modify('+25 hours');
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