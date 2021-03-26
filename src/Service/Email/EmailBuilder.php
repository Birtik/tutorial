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
     * @param string $to
     * @param string $subject
     * @param string $template
     * @param string $token
     * @return TemplatedEmail
     */
    public function buildEmail(string $to, string $subject, string $template, string $token): TemplatedEmail
    {
        return  (new TemplatedEmail())
            ->from($this->from)
            ->to(new Address($to))
            ->subject($subject)
            ->htmlTemplate("registration/{$template}.html.twig")
            ->context(
                [
                    'token' => $token,
                ]
            );

    }

}