<?php

namespace App\Entity;

use App\Repository\AgreementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AgreementRepository::class)
 */
class Agreement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $legalAgreement;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $newsletterAgreement;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    public static function create(bool $legalAgreement, bool $newsletterAgreement, User $user): Agreement
    {
        $obj = new self();
        $obj->legalAgreement = $legalAgreement;
        $obj->newsletterAgreement = $newsletterAgreement;
        $obj->user = $user;
        $obj->updatedAt = new \DateTime();
        return $obj;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLegalAgreement(): bool
    {
        return $this->legalAgreement;
    }

    public function setLegalAgreement(bool $legalAgreement): self
    {
        $this->legalAgreement = $legalAgreement;

        return $this;
    }

    public function getNewsletterAgreement(): bool
    {
        return $this->newsletterAgreement;
    }

    public function setNewsletterAgreement(bool $newsletterAgreement): self
    {
        $this->newsletterAgreement = $newsletterAgreement;

        return $this;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
