<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Entity\UserAgreement;
use App\Repository\AgreementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class AgreementsManager
{
    /**
     * @var AgreementRepository
     */
    private AgreementRepository $agreementRepository;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * AgreementsManager constructor.
     * @param AgreementRepository $agreementRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(AgreementRepository $agreementRepository, EntityManagerInterface $em)
    {
        $this->agreementRepository = $agreementRepository;
        $this->em = $em;
    }

    /**
     * @param FormInterface $form
     * @param User $user
     */
    public function savingAgreementsByUser(FormInterface $form, User $user): void
    {
        $agreements = $this->agreementRepository->findAll();

        foreach ($agreements as $agreement) {
            $rawData = $form->get("terms{$agreement->getId()}");
            $normData = $rawData->getNormData();

            $userAgreement = new UserAgreement();
            $userAgreement->setUser($user);
            $userAgreement->setAgreement($agreement);
            $userAgreement->setChecked($normData);
            $this->em->persist($userAgreement);
        }
        $this->em->flush();
    }
}