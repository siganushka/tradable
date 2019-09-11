<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Entity;

use App\Model\ResourceInterface;
use App\Model\ResourceTrait;
use App\Model\SortableInterface;
use App\Model\SortableTrait;
use App\Model\TimestampableInterface;
use App\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductOptionValueRepository")
 */
class ProductOptionValue implements ResourceInterface, SortableInterface, TimestampableInterface
{
    use ResourceTrait;
    use SortableTrait;
    use TimestampableTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ProductOption", inversedBy="values")
     */
    private $option;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ProductItem", mappedBy="optionValues")
     */
    private $items;

    public function __construct(string $name = null)
    {
        $this->name = $name;
        $this->items = new ArrayCollection();
    }

    public function getOption(): ?ProductOption
    {
        return $this->option;
    }

    public function setOption(?ProductOption $option): self
    {
        $this->option = $option;

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

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(ProductItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->addOptionValue($this);
        }

        return $this;
    }

    public function removeItem(ProductItem $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            $item->removeOptionValue($this);
        }

        return $this;
    }
}
