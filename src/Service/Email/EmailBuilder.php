<?php
declare(strict_types=1);

namespace App\Service\Email;


class EmailBuilder
{
    public function buildConfirmationEmail(string $to, string $from, string $token): TemplatedEmail
    {
        return  (new TemplatedEmail())
            ->from($from)
            ->to(new Address($to))
            ->subject('Miło Cię powitać!')
            ->htmlTemplate('registration/email_template.html.twig')
            ->context(
                [
                    'token' => $token,
                ]
            );

    }

}