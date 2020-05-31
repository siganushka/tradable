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
    use ProductVariantTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="items")
     */
    private $order;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ProductVariant", inversedBy="orderItems")
     */
    private $variant;

    public function __construct(ProductVariant $variant, int $quantity)
    {
        $this->variant = $variant;
        $this->name = $variant->getName();
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

    public function getSubtotal(): int
    {
        return bcmul($this->price, $this->quantity);
    }
}
