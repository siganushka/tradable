<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

trait ProductVariantTrait
{
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
}
