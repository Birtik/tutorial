<?php

namespace App\Service\Email;

interface Builder
{
    public function addFrom(string $from);

    public function addSubject();

    public function addHtmlTemplate();

    public function addContext(string $token);

    public function addTo(string $mailTo);

    public function createEmail();

    public function getEmail();
}