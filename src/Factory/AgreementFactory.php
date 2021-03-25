<?php declare(strict_types=1);

namespace App\Factory;

use App\Entity\Agreement;
use App\Entity\User;
use App\Model\RegisterUserModel;

class AgreementFactory
{
    public function createAgreementFromRegisterUserModel(RegisterUserModel $registerUserModel, User $user): Agreement
    {
        $legalAgreement = $registerUserModel->isLegalAgreement();
        $newsletterAgreement = $registerUserModel->isNewsletterAgreement();

        return Agreement::create($legalAgreement,$newsletterAgreement, $user);
    }
}