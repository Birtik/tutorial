<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
//use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Collection;
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
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $description;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     */
    private Category $category;

    /**
     * @ORM\Column(type="integer")
     */
    private int $amount;

    /**
     * @ORM\OneToMany(targetEntity=Opinion::class, mappedBy="product")
     */
    private Collection $productOpinion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $icon;

    public function __construct()
    {
        $this->productOpinion = new ArrayCollection();
    }

    public static function create(Category $category, int $id, string $name, string $description, int $amount, string $icon): self
    {
        $obj = new self();
        $obj->category = $category;
        $obj->id = $id;
        $obj->name = $name;
        $obj->description = $description;
        $obj->amount = $amount;
        $obj->icon = $icon;

        return $obj;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAmount(): ?int
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

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

}
