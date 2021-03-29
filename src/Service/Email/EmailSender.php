<?php declare(strict_types=1);

namespace App\Service\Email;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class EmailSender
{
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    /**
     * @var Director
     */
    private Director $director;

    public function __construct(MailerInterface $mailer, Director $director)
    {
        $this->mailer = $mailer;
        $this->director = $director;
    }

    /**
     * @param TemplatedEmail $email
     * @throws TransportExceptionInterface
     */
    public function sendMail(TemplatedEmail $email): void
    {
        $this->mailer->send($email);
    }

    /**
     * @param string $mailTo
     * @param string $value
     * @throws TransportExceptionInterface
     */
    public function sendConfirmationEmail(string $mailTo, string $value): void
    {
        $emailBuilder = new EmailConfirmationBuilder();
        $email = $this->director->build($emailBuilder,$mailTo, $value);
        $this->sendMail($email);
    }

    /**
     * @param string $mailTo
     * @throws TransportExceptionInterface
     */
    public function sendDoubleRegistrationAlertEmail(string $mailTo): void
    {
        $emailBuilder = new EmailAlertBuilder();
        $email = $this->director->build($emailBuilder,$mailTo);
        $this->sendMail($email);
    }
}