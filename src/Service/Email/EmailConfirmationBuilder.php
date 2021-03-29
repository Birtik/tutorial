<?php
declare(strict_types=1);

namespace App\Service\Email;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class EmailConfirmationBuilder implements Builder
{
    /**
     * @var TemplatedEmail
     */
    private TemplatedEmail $email;

    public function addHtmlTemplate(): void
    {
        $this->email->htmlTemplate('registration/email_template.html.twig');
    }

    public function addSubject(): void
    {
        $this->email->subject('Miło Cię powitać!');
    }

    /**
     * @param string $from
     */
    public function addFrom(string $from): void
    {
        $this->email->from($from);
    }

    /**
     * @param string $token
     */
    public function addContext(string $token): void
    {
        $this->email->context(
            [
                'token' => $token,
            ]
        );
    }

    /**
     * @param string $mailTo
     */
    public function addTo(string $mailTo): void
    {
        $this->email->to($mailTo);
    }

    public function createEmail(): void
    {
        $this->email = new TemplatedEmail();
    }

    /**
     * @return TemplatedEmail
     */
    public function getEmail(): TemplatedEmail
    {
        return $this->email;
    }
}