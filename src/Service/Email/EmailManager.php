<?php declare(strict_types=1);

namespace App\Service\Email;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class EmailManager implements EmailManagerInterface
{
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    /**
     * @var EmailBuilderInterface
     */
    private EmailBuilderInterface $emailBuilder;

    public function __construct(MailerInterface $mailer, EmailBuilderInterface $emailBuilder)
    {
        $this->mailer = $mailer;
        $this->emailBuilder = $emailBuilder;
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
     * @param string $token
     * @throws TransportExceptionInterface
     */
    public function sendConfirmationEmail(string $mailTo, string $token): void
    {
        $email = $this->emailBuilder
            ->createEmail()
            ->addTo($mailTo)
            ->addSubject('Miło Cię powitać!')
            ->addContext(['token' => $token])
            ->addHtmlTemplate('registration/email_template.html.twig')
            ->getEmail()
        ;

        $this->sendMail($email);
    }

    /**
     * @param string $mailTo
     * @throws TransportExceptionInterface
     */
    public function sendDoubleRegistrationAlertEmail(string $mailTo): void
    {
        $email = $this->emailBuilder
            ->createEmail()
            ->addTo($mailTo)
            ->addSubject('Ktoś próbował założyć konto na Twój adres email!')
            ->addHtmlTemplate('registration/email_template_alert.html.twig')
            ->getEmail()
        ;

        $this->sendMail($email);
    }
}