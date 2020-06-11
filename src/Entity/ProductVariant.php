<?php

namespace App\Entity;

use App\Exception\InvalidOptionChoiceKeyException;
use App\Generator\ProductVariantGenerator;
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
     *
     * @Assert\NotBlank()
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank()
     */
    private $price;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Assert\GreaterThanOrEqual(0)
     */
    private $inventory;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     *
     * @Assert\NotBlank()
     */
    private $optionChoiceKey;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderItem", mappedBy="variant")
     */
    private $orderItems;

    public function __construct()
    {
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

    public function getOptionChoiceKey(): ?string
    {
        return $this->optionChoiceKey;
    }

    public function setOptionChoiceKey(?string $optionChoiceKey): self
    {
        $this->optionChoiceKey = $optionChoiceKey;

        return $this;
    }

    public function getOptionChoiceValue()
    {
        $generator = new ProductVariantGenerator($this->product);

        if (!$generator->offsetExists($this->optionChoiceKey)) {
            throw new InvalidOptionChoiceKeyException($this);
        }

        return $generator->offsetGet($this->optionChoiceKey);
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
