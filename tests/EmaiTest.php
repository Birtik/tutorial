<?php declare(strict_types=1);

namespace App\Tests;

use App\Service\Email\EmailBuilder;
use App\Service\Email\EmailSender;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmaiTest extends TestCase
{
    public function testConfirmationEMailBuilder(): void
    {
        $to = "testMailTo@wp.pl";
        $subject = "Miło Cię powitać!";
        $template = "registration/email_template.html.twig";
        $token = "1234";

        $mailer = new EmailBuilder($to);
        $email = $mailer->buildConfirmationEmail($to, $token);
        $namedAddresses = $email->getTo();
        $context = $email->getContext();
        $contextToken = $context['token'];
        $htmlTemplate = $email->getHtmlTemplate();

        self::assertSame($subject, $email->getSubject());
        self::assertSame($to, $namedAddresses[0]->getAddress());
        self::assertSame($template, $htmlTemplate);
        self::assertSame($token, $contextToken);
    }

    public function testRepeatedUserEmailBuilder(): void
    {
        $to = "testMailTo@wp.pl";
        $subject = "Ktoś próbował założyć konto na Twój adres email!";
        $template = "registration/email_template_alert.html.twig";

        $mailer = new EmailBuilder($to);
        $email = $mailer->buildRepeatedUserEmail($to);
        $namedAddresses = $email->getTo();
        $htmlTemplate = $email->getHtmlTemplate();

        self::assertSame($subject, $email->getSubject());
        self::assertSame($to, $namedAddresses[0]->getAddress());
        self::assertSame($template, $htmlTemplate);
    }

    public function testSendingEmail(): void
    {
        $to = "testMailTo@wp.pl";
        $token = "1234";

        $buildMock = $this->getMockBuilder(EmailBuilder::class)
            ->setConstructorArgs(['from@wp.pl'])->getMock();
        $buildMock->expects(self::once())->method('buildConfirmationEmail')->willReturn(new TemplatedEmail());
        $buildMock->expects(self::once())->method('buildRepeatedUserEmail')->willReturn(new TemplatedEmail());

        $mailerInterfaceMock = $this->getMockBuilder(MailerInterface::class)->getMock();
        $mailerInterfaceMock->expects(self::exactly(2))->method('send');

        $emailSender = new EmailSender($mailerInterfaceMock, $buildMock);
        $emailSender->sendConfirmationEmail($to,$token);
        $emailSender->sendDoubleRegistrationAlertEmail($to);
    }
}