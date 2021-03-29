<?php
declare(strict_types=1);

namespace App\Service\Email;


interface EmailManagerInterface
{
    public function sendConfirmationEmail(string $mailTo, string $token): void;

    public function sendDoubleRegistrationAlertEmail(string $mailTo): void;
}