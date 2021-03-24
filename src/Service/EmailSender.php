<?php declare(strict_types=1);

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;


class EmailSender
{
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param string $mail
     * @param string $value
     * @throws TransportExceptionInterface
     */
    public function sendConfirmationEmail(string $mail, string $value): void
    {
        $email = (new TemplatedEmail())
            ->from('teamOfTestShop@o2.com')
            ->to(new Address($mail))
            ->subject('Miło Cię powitać!')
            ->htmlTemplate('registration/email_template.html.twig')
            ->context(
                [
                    'token' => $value,
                ]
            );

        $this->mailer->send($email);
    }

    /**
     * @param string $mail
     * @throws TransportExceptionInterface
     */
    public function sendDoubleRegistrationAlertEmail(string $mail): void
    {
        $email = (new TemplatedEmail())
            ->from('teamOfTestShop@o2.com')
            ->to(new Address($mail))
            ->subject('Ktoś próbował założyć konto na Twój adres email')
            ->htmlTemplate('registration/email_template_alert.html.twig')
             ;

        $this->mailer->send($email);
    }
}