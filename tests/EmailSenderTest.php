<?php declare(strict_types=1);


namespace App\Tests;


use App\Service\Email\EmailBuilder;
use App\Service\Email\EmailSender;
use Symfony\Component\Mime\Address;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;

class EmailSenderTest extends TestCase
{
    public function testSend()
    {
        $to = "test@wp.pl";
        $subject = "Miło Cię powitać!";
        $template = "testTemplate";
        $token = "1234";

        $mailer = new EmailBuilder($to);
        $email = $mailer->buildConfirmationEmail($to,$token);

        $namedAddresses = $email->getTo();

        self::assertSame($subject,$email->getSubject());
        self::assertSame($to,$namedAddresses[0]->getAddress());
    }
}