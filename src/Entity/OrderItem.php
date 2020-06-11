<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Siganushka\GenericBundle\Model\ResourceInterface;
use Siganushka\GenericBundle\Model\ResourceTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderItemRepository")
 */
class OrderItem implements ResourceInterface
{
    use ResourceTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="items")
     */
    private $order;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ProductVariant", inversedBy="orderItems")
     */
    private $variant;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantity;

    public function __construct(ProductVariant $variant, int $quantity)
    {
        $product = $variant->getProduct();

        // generate variant name for order item.
        $name = $product->getName();
        if (!$variant->getOptionValues()->isEmpty()) {
            $name .= sprintf(' (%s)', $variant->getOptionValuesNames());
        }

        $this->variant = $variant;
        $this->name = $name;
        $this->price = $variant->getPrice();
        $this->quantity = $quantity;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getVariant(): ProductVariant
    {
        return $this->variant;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): ?self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): ?self
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): ?self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getSubtotal(): int
    {
        return bcmul($this->price, $this->quantity);
    }
}
