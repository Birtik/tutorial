<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_product"})
     */
    private string $name;

    /**
     * @ORM\Column(type="text")
     * @Groups({"list_product"})
     */
    private string $description;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     * @Groups({"list_product"})
     */
    private Category $category;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"list_product"})
     */
    private int $amount;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private string $icon;

    /**
     * @ORM\OneToMany(targetEntity=Opinion::class, mappedBy="product")
     */
    private Collection $productOpinion;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"list_product"})
     */
    private int $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $affiliationLink;

    /**
     * @ORM\Column(type="integer")
     */
    private int $affiliationCounter;

    public function __construct()
    {
        $this->productOpinion = new ArrayCollection();
    }

    public static function create(
        Category $category,
        string $name,
        string $description,
        int $amount,
        string $icon,
        int $price,
        ?string $affiliationLink,
        int $affiliationCounter
    ): self {
        $obj = new self();
        $obj->category = $category;
        $obj->name = $name;
        $obj->description = $description;
        $obj->amount = $amount;
        $obj->icon = $icon;
        $obj->price = $price;
        $obj->affiliationLink = $affiliationLink;
        $obj->affiliationCounter = $affiliationCounter;

        return $obj;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return Collection|Opinion[]
     */
    public function getProductOpinion(): Collection
    {
        return $this->productOpinion;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getAffiliationLink(): ?string
    {
        return $this->affiliationLink;
    }

    public function setAffiliationLink(?string $affiliationLink): self
    {
        $this->affiliationLink = $affiliationLink;

        return $this;
    }

    public function getAffiliationCounter(): int
    {
        return $this->affiliationCounter;
    }

    public function setAffiliationCounter(int $affiliationCounter): self
    {
        $this->affiliationCounter = $affiliationCounter;

        return $this;
    }

}
