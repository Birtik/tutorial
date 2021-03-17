<?php declare(strict_types=1);

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
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

    public function sendEmail(string $mail, string $value): void
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
}