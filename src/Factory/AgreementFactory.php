<?php declare(strict_types=1);

namespace App\Factory;

use App\Entity\Agreement;
use App\Entity\User;

class AgreementFactory
{
    public function create(bool $legalAgreement, bool $newsletterAgreement, User $user): Agreement
    {
        return Agreement::create($legalAgreement,$newsletterAgreement, $user);
    }
}