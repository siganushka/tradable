<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Entity;

use App\Model\EnableInterface;
use App\Model\EnableTrait;
use App\Model\ResourceInterface;
use App\Model\ResourceTrait;
use App\Model\TimestampableInterface;
use App\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product implements ResourceInterface, EnableInterface, TimestampableInterface
{
    use ResourceTrait;
    use EnableTrait;
    use TimestampableTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="products")
     *
     * @Assert\NotBlank()
     */
    private $category;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    private $unit;

    /**
     * @ORM\Column(type="json")
     */
    private $attributeValues = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductOption", mappedBy="product", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"sort": "ASC"})
     */
    private $options;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductItem", mappedBy="product", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"createdAt": "ASC"})
     */
    private $items;

    public function __construct()
    {
        $this->options = new ArrayCollection();
        $this->items = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getAttributeValues(): ?array
    {
        return $this->attributeValues;
    }

    public function setAttributeValues(?array $attributeValues): self
    {
        $this->attributeValues = $attributeValues;

        return $this;
    }

    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(ProductOption $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
            $option->setProduct($this);
        }

        return $this;
    }

    public function removeOption(ProductOption $option): self
    {
        if ($this->options->contains($option)) {
            $this->options->removeElement($option);
            if ($option->getProduct() === $this) {
                $option->setProduct(null);
            }
        }

        return $this;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(ProductItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setProduct($this);
        }

        return $this;
    }

    public function removeItem(ProductItem $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            if ($item->getProduct() === $this) {
                $item->setProduct(null);
            }
        }

        return $this;
    }
}
