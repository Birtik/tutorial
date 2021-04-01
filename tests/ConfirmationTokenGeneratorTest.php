<?php declare(strict_types=1);

namespace App\Tests;

use App\Entity\Token;
use App\Entity\User;
use App\Service\ConfirmationTokenGenerator;
use App\Service\DateTimeProvider;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**s
 * @group time-sensitive
 */
class ConfirmationTokenGeneratorTest extends TestCase
{
    /**
     * @var MockObject|DateTimeProvider
     */
    private MockObject $dateTimeProviderMock;
    /**
     * @var MockObject|User
     */
    private MockObject $userMock;
    /**
     * @var MockObject|EntityManager
     */
    private MockObject $entityManagerMock;

    protected function setUp(): void
    {
        $this->dateTimeProviderMock = $this->createMock(DateTimeProvider::class);
        $this->userMock = $this->createMock(User::class);
        $this->entityManagerMock = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGenerateFirstUserToken(): void
    {
        $this->dateTimeProviderMock
            ->expects(self::once())
            ->method('getCurrentTime')
            ->willReturn(5612);

        $this->dateTimeProviderMock
            ->expects(self::once())
            ->method('getCurrentDateTime')
            ->willReturn(new \DateTime('2021-03-01'));

        $this->userMock
            ->expects(self::once())
            ->method('getEmail')
            ->willReturn('test');

        $this->entityManagerMock
            ->expects(self::once())
            ->method('persist');

        $this->entityManagerMock
            ->expects(self::once())
            ->method('flush');

        $expectHash = md5('test5612');
        $testGenerator = new ConfirmationTokenGenerator($this->entityManagerMock, $this->dateTimeProviderMock);
        $token = $testGenerator->generateTokenForUser($this->userMock);

        self::assertSame(32, strlen($token->getValue()));
        self::assertSame($expectHash, $token->getValue());
        self::assertEquals((new \DateTime('2021-03-01'))->modify('+25 hours'), $token->getExpiredAt());
        self::assertSame(1, $token->getType());
    }

    public function testGenerateSecondUserToken(): void
    {
        $this->dateTimeProviderMock
            ->expects(self::once())
            ->method('getCurrentTime')
            ->willReturn(1234);

        $this->dateTimeProviderMock
            ->expects(self::once())
            ->method('getCurrentDateTime')
            ->willReturn(new \DateTime('2021-02-01'));

        $this->userMock
            ->expects(self::once())
            ->method('getEmail')
            ->willReturn('$!@#123qwerty');

        $expectHash = md5('$!@#123qwerty1234');
        $testGenerator = new ConfirmationTokenGenerator($this->entityManagerMock, $this->dateTimeProviderMock);
        $token = $testGenerator->generateTokenForUser($this->userMock);

        self::assertSame(32, strlen($token->getValue()));
        self::assertSame($expectHash, $token->getValue());
        self::assertEquals((new \DateTime('2021-02-01'))->modify('+25 hours'), $token->getExpiredAt());
        self::assertSame(1, $token->getType());
    }
}
