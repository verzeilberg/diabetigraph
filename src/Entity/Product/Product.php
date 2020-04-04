<?php

namespace App\Entity\Product;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table(name="product",uniqueConstraints={@ORM\UniqueConstraint(name="search_idx", columns={"name"})})
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", nullable=false, )
     */
    private $archived = false;

    /**
     * Many products have one unit. This is the owning side.
     * @ORM\ManyToOne(targetEntity="Unit", inversedBy="products")
     * @ORM\JoinColumn(name="unit_id", referencedColumnName="id", nullable=false)
     */
    private $unit;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $carbohydrates;

    /**
     * Many products have one product group. This is the owning side.
     * @ORM\ManyToOne(targetEntity="ProductGroup", inversedBy="products")
     * @ORM\JoinColumn(name="product_group_id", referencedColumnName="id", nullable=true)
     */
    private $productGroup;

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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductGroup()
    {
        return $this->productGroup;
    }

    /**
     * @param mixed $productGroup
     * @return Product
     */
    public function setProductGroup($productGroup)
    {
        $this->productGroup = $productGroup;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getArchived()
    {
        return $this->archived;
    }

    /**
     * @param mixed $archived
     * @return Product
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param mixed $unit
     * @return Product
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCarbohydrates()
    {
        return $this->carbohydrates;
    }

    /**
     * @param mixed $carbohydrates
     * @return Product
     */
    public function setCarbohydrates($carbohydrates)
    {
        $this->carbohydrates = $carbohydrates;
        return $this;
    }



}
