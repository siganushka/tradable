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

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductVariantRepository")
 * @App\Validator\UniqueProductVariant
 */
class ProductVariant implements ResourceInterface, EnableInterface, TimestampableInterface
{
    use ResourceTrait;
    use ProductVariantTrait;
    use EnableTrait;
    use TimestampableTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="variants")
     */
    private $product;

    /**
     * @ORM\ManyToMany(targetEntity="ProductOptionValue", inversedBy="variants")
     * @ORM\JoinTable(name="product_variant_option_value")
     */
    private $optionValues;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderItem", mappedBy="variant")
     */
    private $orderItems;

    public function __construct()
    {
        $this->optionValues = new ArrayCollection();
        $this->orderItems = new ArrayCollection();
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        if (null === $this->name) {
            $this->name = $product->getName();
        }

        return $this;
    }

    public function getOptionValuesToken()
    {
        $tokenAsArray = $this->optionValues->map(function (ProductOptionValue $item) {
            return $item->getId();
        })->toArray();

        sort($tokenAsArray);

        return implode('_', $tokenAsArray);
    }

    public function getOptionValues(): Collection
    {
        return $this->optionValues;
    }

    public function setOptionValues(iterable $optionValues): self
    {
        foreach ($optionValues as $optionValue) {
            $this->addOptionValue($optionValue);
        }

        return $this;
    }

    public function addOptionValue(?ProductOptionValue $optionValue): self
    {
        if (null === $optionValue) {
            return $this;
        }

        if (!$this->optionValues->contains($optionValue)) {
            $this->optionValues[] = $optionValue;
        }

        return $this;
    }

    public function removeOptionValue(ProductOptionValue $optionValue): self
    {
        if ($this->optionValues->contains($optionValue)) {
            $this->optionValues->removeElement($optionValue);
        }

        return $this;
    }

    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): self
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems[] = $orderItem;
            $orderItem->setItem($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): self
    {
        if ($this->orderItems->contains($orderItem)) {
            $this->orderItems->removeElement($orderItem);
            if ($orderItem->getItem() === $this) {
                $orderItem->setItem(null);
            }
        }

        return $this;
    }
}
