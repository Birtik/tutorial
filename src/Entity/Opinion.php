<?php

namespace App\Entity;

use App\Repository\OpinionRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=OpinionRepository::class)
 */
class Opinion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $comment;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $nickname;

    /**
     * @ORM\Column(type="integer")
     */
    private int $rate;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="productOpinion")
     */
    private Product $product;

    public static function create(Product $product, string $comment, string $nickname, int $rate): self
    {
        $obj = new self();
        $obj->comment = $comment;
        $obj->nickname = $nickname;
        $obj->rate = $rate;
        $obj->product = $product;

        return $obj;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getRate(): int
    {
        return $this->rate;
    }

    public function setRate(int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
