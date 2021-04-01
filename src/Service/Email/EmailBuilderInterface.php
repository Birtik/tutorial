<?php

namespace App\Service\Email;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;

interface EmailBuilderInterface
{
    public function addFrom(string $from): EmailBuilderInterface;

    public function addSubject(string $subject): EmailBuilderInterface;

    public function addHtmlTemplate(string $template): EmailBuilderInterface;

    public function addContext(array $context): EmailBuilderInterface;

    public function addTo(string $mailTo): EmailBuilderInterface;

    public function createEmail(): EmailBuilderInterface;

    public function getEmail(): TemplatedEmail;
}