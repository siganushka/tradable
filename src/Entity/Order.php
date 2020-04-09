<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Siganushka\GenericBundle\Model\ResourceInterface;
use Siganushka\GenericBundle\Model\ResourceTrait;
use Siganushka\GenericBundle\Model\TimestampableInterface;
use Siganushka\GenericBundle\Model\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class Order implements ResourceInterface, TimestampableInterface
{
    use ResourceTrait;
    use TimestampableTrait;

    const STATE_PENDING = 'pending';
    const STATE_ACCEPTED = 'accepted';
    const STATE_REFUNDED = 'refunded';
    const STATE_COMPLETED = 'completed';
    const STATE_CANCELLED = 'cancelled';
    const STATE_EXPIRED = 'expired';
    const STATE_CLOSED = 'closed';

    public static $_state = [
        self::STATE_PENDING => 'order.state.pending',
        self::STATE_ACCEPTED => 'order.state.accepted',
        self::STATE_REFUNDED => 'order.state.refunded',
        self::STATE_COMPLETED => 'order.state.completed',
        self::STATE_CANCELLED => 'order.state.cancelled',
        self::STATE_EXPIRED => 'order.state.expired',
        self::STATE_CLOSED => 'order.state.closed',
    ];

    /**
     * @ORM\Column(type="string", length=16, unique=true, options={"fixed": true})
     */
    private $number;

    /**
     * @ORM\Column(type="string")
     */
    private $state;

    /**
     * @ORM\Column(type="integer")
     */
    private $itemsTotal;

    /**
     * @ORM\Column(type="integer")
     */
    private $total;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderItem", mappedBy="order", cascade={"all"}, orphanRemoval=true)
     */
    private $items;

    public function __construct()
    {
        $this->itemsTotal = 0;
        $this->total = 0;
        $this->items = new ArrayCollection();
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        if (!isset(self::$_state[$state])) {
            throw new \InvalidArgumentException(sprintf('The state "%s" does not exist. Defined states are: "%s".', $state, implode('", "', array_keys(self::$_state))));
        }

        $this->state = $state;

        return $this;
    }

    public function getItemsTotal(): ?int
    {
        return $this->itemsTotal;
    }

    public function setItemsTotal(int $itemsTotal): self
    {
        $this->itemsTotal = $itemsTotal;

        return $this;
    }

    public function recalculateItemsTotal()
    {
        $itemsTotal = 0;
        foreach ($this->items as $item) {
            $itemsTotal += $item->getSubtotal();
        }

        $this->itemsTotal = $itemsTotal;
        $this->recalculateTotal();

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function recalculateTotal()
    {
        return $this->total = $this->itemsTotal;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function clearItems(): self
    {
        $this->items->clear();
        $this->recalculateItemsTotal();

        return $this;
    }

    public function addItem(OrderItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setOrder($this);
            $this->recalculateItemsTotal();
        }

        return $this;
    }

    public function removeItem(OrderItem $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            if ($item->getOrder() === $this) {
                $item->setOrder(null);
                $this->recalculateItemsTotal();
            }
        }

        return $this;
    }
}
