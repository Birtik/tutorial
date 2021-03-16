<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Token;
use App\Entity\User;
use DateTimeImmutable;
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

    public function generateToken(string $str): string
    {
        $token = new Token();
        $token->setValue(md5(sprintf("{$str}%s", time())));
        $token->setUser($this->em->getRepository(User::class)->findOneBy(['email' => $str]));
        $token->setType(Token::TYPE_REGISTER);
        $token->setExpiredAt(
            (new DateTimeImmutable())->setDate(getdate()['year'], getdate()['mon'] + 1, getdate()['mday'])
        );

        $this->em->persist($token);
        $this->em->flush();

        return $token->getValue();
    }
}