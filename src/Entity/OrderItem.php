<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Entity;

use App\Model\ResourceInterface;
use App\Model\ResourceTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderItemRepository")
 */
class OrderItem implements ResourceInterface
{
    use ResourceTrait;
    use ProductItemTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="items")
     */
    private $order;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ProductItem", inversedBy="orderItems")
     */
    private $item;

    public function __construct(ProductItem $item, int $quantity)
    {
        $this->item = $item;
        $this->name = $item->getName();
        $this->price = $item->getPrice();
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

    public function getItem(): ProductItem
    {
        return $this->item;
    }

    public function getSubtotal(): int
    {
        return bcmul($this->price, $this->quantity);
    }
}
