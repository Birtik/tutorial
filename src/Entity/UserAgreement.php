<?php

namespace App\Entity;

use App\Repository\UserAgreementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserAgreementRepository::class)
 */
class UserAgreement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity=Agreement::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private Agreement $agreement;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $checked;

    public function getId(): int
    {
        return $this->id;
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

    public function getAgreement(): Agreement
    {
        return $this->agreement;
    }

    public function setAgreement(Agreement $agreement): self
    {
        $this->agreement = $agreement;

        return $this;
    }

    public function getChecked(): bool
    {
        return $this->checked;
    }

    public function setChecked(bool $checked): self
    {
        $this->checked = $checked;

        return $this;
    }
}
