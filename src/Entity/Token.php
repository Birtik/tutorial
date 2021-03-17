<?php

namespace App\Entity;

use App\Repository\TokenRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TokenRepository::class)
 */
class Token
{
    public const TYPE_REGISTER = 1;
    public const TYPE_RESET = 2;

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
     * @ORM\Column(type="string", length=32)
     */
    private string $value;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $expiredAt;

    /**
     * @ORM\Column(type="integer")
     */
    private int $type;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $usedAt;

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

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getExpiredAt(): DateTimeInterface
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(DateTimeInterface $expiredAt): self
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUsedAt(): ?DateTimeInterface
    {
        return $this->usedAt;
    }

    public function setUsedAt(?DateTimeInterface $usedAt): self
    {
        $this->usedAt = $usedAt;

        return $this;
    }
}
