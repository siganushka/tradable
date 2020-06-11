<?php

namespace App\Entity;

use App\Utils\OptionValueUtils;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Siganushka\GenericBundle\Model\EnableInterface;
use Siganushka\GenericBundle\Model\EnableTrait;
use Siganushka\GenericBundle\Model\ResourceInterface;
use Siganushka\GenericBundle\Model\ResourceTrait;
use Siganushka\GenericBundle\Model\TimestampableInterface;
use Siganushka\GenericBundle\Model\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductVariantRepository")
 *
 * @App\Validator\UniqueProductVariant
 */
class ProductVariant implements ResourceInterface, EnableInterface, TimestampableInterface
{
    use ResourceTrait;
    use EnableTrait;
    use TimestampableTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="variants")
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $inventory;

    /**
     * @ORM\ManyToMany(targetEntity="ProductOptionValue", inversedBy="variants")
     * @ORM\JoinTable(name="product_variant_option_value")
     *
     * @Assert\Count(min=1)
     * @Assert\Valid()
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

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): ?self
    {
        $this->price = $price;

        return $this;
    }

    public function getInventory(): ?int
    {
        return $this->inventory;
    }

    public function setInventory(int $inventory): ?self
    {
        $this->inventory = $inventory;

        return $this;
    }

    public function getOptionValuesIds()
    {
        return (new OptionValueUtils($this->optionValues))->getIds();
    }

    public function getOptionValuesNames()
    {
        return (new OptionValueUtils($this->optionValues))->getNames();
    }

    public function getOptionValues(): Collection
    {
        return $this->optionValues;
    }

    public function setOptionValues(?Collection $optionValues): self
    {
        if (null === $optionValues) {
            return $this;
        }

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
