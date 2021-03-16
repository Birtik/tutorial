<?php declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

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

    public function sendEmail(string $mail, string $token): void
    {
        $email = (new Email())
            ->from('teamOfTestShop@o2.com')
            ->to($mail)
            ->subject('Miło Cię powitać!')
            ->html("<h1>Witaj na pokładzie!</h1><p>Przejdź pod link: <a target='_blank' href='http://localhost/confirm/email/".$token."'>Potwierdź</a></p>");

        $this->mailer->send($email);
    }
}