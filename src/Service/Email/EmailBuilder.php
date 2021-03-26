<?php
declare(strict_types=1);

namespace App\Service\Email;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class EmailBuilder
{
    /**
     * @var string
     */
    private string $from;

    /**
     * EmailBuilder constructor.
     * @param string $from
     */
    public function __construct(string $from)
    {
        $this->from = $from;
    }

    /**
     * @param string $mailTo
     * @param string $token
     * @return TemplatedEmail
     */
    public function buildConfirmationEmail(string $mailTo, string $token): TemplatedEmail
    {
        return  (new TemplatedEmail())
            ->from($this->from)
            ->to(new Address($mailTo))
            ->subject('Miło Cię powitać!')
            ->htmlTemplate("registration/email_template.html.twig")
            ->context(
                [
                    'token' => $token,
                ]
            );
    }

    /**
     * @param string $mailTo
     * @return TemplatedEmail
     */
    public function buildRepeatedUserEmail(string $mailTo): TemplatedEmail
    {
        return  (new TemplatedEmail())
            ->from($this->from)
            ->to(new Address($mailTo))
            ->subject("Ktoś próbował założyć konto na Twój adres email!")
            ->htmlTemplate("registration/email_template_alert.html.twig");
    }
}