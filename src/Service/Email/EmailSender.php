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
     * @param string $mail
     * @param string $value
     * @throws TransportExceptionInterface
     */
    public function sendConfirmationEmail(string $mail, string $value): void
    {
        $subject = 'Miło Cię powitać!';
        $template = "email_template";

        $email = $this->emailBuilder->buildEmail($mail, $subject, $template, $value);
        $this->send($email);
    }

    /**
     * @param string $mail
     * @throws TransportExceptionInterface
     */
    public function sendDoubleRegistrationAlertEmail(string $mail): void
    {
        $subject = 'Ktoś próbował założyć konto na Twój adres email!';
        $template = "email_template_alert";

        $email = $this->emailBuilder->buildEmail($mail, $subject, $template,'');
        $this->send($email);
    }
}