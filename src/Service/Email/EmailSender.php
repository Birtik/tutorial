<?php declare(strict_types=1);

namespace App\Service\Email;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class EmailSender
{
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    /**
     * @var EmailBuilder
     */
    private EmailBuilder $emailBuilder;

    public function __construct(MailerInterface $mailer, EmailBuilder $emailBuilder)
    {
        $this->mailer = $mailer;
        $this->emailBuilder = $emailBuilder;
    }

    /**
     * @param TemplatedEmail $email
     * @throws TransportExceptionInterface
     */
    public function send(TemplatedEmail $email): void
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
        $email = $this->emailBuilder->buildConfirmationEmail($mailTo, $value);
        $this->send($email);
    }

    /**
     * @param string $mailTo
     * @throws TransportExceptionInterface
     */
    public function sendDoubleRegistrationAlertEmail(string $mailTo): void
    {
        $email = $this->emailBuilder->buildRepeatedUserEmail($mailTo);
        $this->send($email);
    }
}