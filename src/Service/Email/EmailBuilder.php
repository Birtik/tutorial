<?php
declare(strict_types=1);

namespace App\Service\Email;


use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class EmailBuilder implements EmailBuilderInterface
{
    private string $defaultFrom;

    private ?TemplatedEmail $templatedEmail;

    public function __construct(string $defaultFrom)
    {
        $this->defaultFrom = $defaultFrom;
    }

    public function addFrom(string $from): EmailBuilderInterface
    {
        $this->templatedEmail->from($from);

        return $this;
    }

    public function addSubject(string $subject): EmailBuilderInterface
    {
        $this->templatedEmail->subject($subject);

        return $this;
    }

    public function addHtmlTemplate(string $template): EmailBuilderInterface
    {
        $this->templatedEmail->htmlTemplate($template);

        return $this;
    }

    public function addContext(array $context): EmailBuilderInterface
    {
        $this->templatedEmail->context($context);

        return $this;
    }

    public function addTo(string $mailTo): EmailBuilderInterface
    {
        $this->templatedEmail->addTo($mailTo);

        return $this;
    }

    public function createEmail(): EmailBuilderInterface
    {
        $this->templatedEmail = new TemplatedEmail();
        $this->templatedEmail->from($this->defaultFrom);

        return $this;
    }

    public function getEmail(): TemplatedEmail
    {
        return $this->templatedEmail;
    }
}