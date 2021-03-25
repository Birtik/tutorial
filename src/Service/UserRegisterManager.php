<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Agreement;
use App\Entity\User;
use App\Factory\AgreementFactory;
use App\Factory\UserFactory;
use App\Model\RegisterUserModel;

class UserRegisterManager
{
    /**
     * @var UserFactory
     */
    private UserFactory $userFactory;

    /**
     * @var AgreementFactory
     */
    private AgreementFactory $agreementFactory;

    public function __construct(UserFactory $userFactory, AgreementFactory $agreementFactory)
    {
        $this->userFactory = $userFactory;
        $this->agreementFactory = $agreementFactory;
    }

    /**
     * @param RegisterUserModel $registerUserModel
     * @return User
     */
    public function setUserData(RegisterUserModel $registerUserModel): User
    {
        $userMail = $registerUserModel->getEmail();
        $userPassword = $registerUserModel->getPassword();
        $userFirstName = $registerUserModel->getFirstName();
        $userLastName = $registerUserModel->getLastName();

        return $this->userFactory->create($userMail, $userPassword, $userFirstName, $userLastName);
    }

    /**
     * @param RegisterUserModel $registerUserModel
     * @param User $user
     * @return Agreement
     */
    public function setUserAgreement(RegisterUserModel $registerUserModel, User $user): Agreement
    {
        $legalAgreement = $registerUserModel->isLegalAgreement();
        $newsletterAgreement = $registerUserModel->isNewsletterAgreement();

        return $this->agreementFactory->create($legalAgreement, $newsletterAgreement, $user);
    }
}