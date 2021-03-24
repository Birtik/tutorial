<?php declare(strict_types=1);

namespace App\Tests;

use App\Entity\Token;
use App\Entity\User;
use App\Service\ConfirmationTokenGenerator;
use App\Service\DateTimeProvider;
use DateTime;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

/**s
 * @group time-sensitive
 */
class ConfirmationTokenGeneratorTest extends TestCase
{
    public function testGenerateFirstUserToken(): void
    {
        $dateTimeProviderMock = $this->getMockBuilder(DateTimeProvider::class)->getMock();
        $dateTimeProviderMock
            ->expects(self::once())
            ->method('getCurrentTime')
            ->willReturn(5612);

        $dateTimeProviderMock
            ->expects(self::once())
            ->method('getCurrentDateTime')
            ->willReturn(new DateTime('2021-03-01'));

        $userMock = $this->getMockBuilder(User::class)->getMock();
        $userMock
            ->expects(self::once())
            ->method('getEmail')
            ->willReturn('test');

        $entityManagerMock = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $entityManagerMock
            ->expects(self::once())
            ->method('persist');

        $entityManagerMock
            ->expects(self::once())
            ->method('flush');

        $expectHash = md5('test5612');
        $testGenerator = new ConfirmationTokenGenerator($entityManagerMock, $dateTimeProviderMock);
        $token = $testGenerator->generateTokenForUser($userMock);

        self::assertEquals(32, strlen($token->getValue()));
        self::assertEquals($expectHash, $token->getValue());
        self::assertEquals((new DateTime('2021-03-01'))->modify('+25 hours'),$token->getExpiredAt());
        self::assertEquals(1,$token->getType());
        self::assertInstanceOf(Token::class,$token);
        self::assertInstanceOf(User::class, $token->getUser());
        self::assertInstanceOf(DateTime::class,$token->getExpiredAt());
        self::assertIsNotArray(Token::class,$token->getValue());
        self::assertIsNotBool(Token::class,$token->getValue());
        self::assertIsNotFloat(Token::class,$token->getValue());
        self::assertIsString(Token::class, $token->getValue());
    }

    public function testGenerateSecondUserToken(): void
    {
        $dateTimeProviderMock = $this->getMockBuilder(DateTimeProvider::class)->getMock();
        $dateTimeProviderMock
            ->expects(self::once())
            ->method('getCurrentTime')
            ->willReturn(1234);

        $dateTimeProviderMock
            ->expects(self::once())
            ->method('getCurrentDateTime')
            ->willReturn(new DateTime('2021-02-01'));

        $userMock = $this->getMockBuilder(User::class)->getMock();
        $userMock
            ->expects(self::once())
            ->method('getEmail')
            ->willReturn('$!@#123qwerty');

        $entityManagerMock = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $expectHash = md5('$!@#123qwerty1234');
        $testGenerator = new ConfirmationTokenGenerator($entityManagerMock, $dateTimeProviderMock);
        $token = $testGenerator->generateTokenForUser($userMock);

        self::assertEquals(32, strlen($token->getValue()));
        self::assertEquals($expectHash, $token->getValue());
        self::assertEquals((new DateTime('2021-02-01'))->modify('+25 hours'),$token->getExpiredAt());
        self::assertEquals(1,$token->getType());
        self::assertInstanceOf(Token::class,$token);
        self::assertInstanceOf(User::class, $token->getUser());
        self::assertInstanceOf(DateTime::class,$token->getExpiredAt());
        self::assertIsNotArray(Token::class,$token->getValue());
        self::assertIsNotBool(Token::class,$token->getValue());
        self::assertIsNotFloat(Token::class,$token->getValue());
        self::assertIsString(Token::class, $token->getValue());
    }
}
