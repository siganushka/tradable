<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Siganushka\GenericBundle\Model\ResourceInterface;
use Siganushka\GenericBundle\Model\ResourceTrait;
use Siganushka\GenericBundle\Model\SortableInterface;
use Siganushka\GenericBundle\Model\SortableTrait;
use Siganushka\GenericBundle\Model\TimestampableInterface;
use Siganushka\GenericBundle\Model\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductOptionRepository")
 */
class ProductOption implements ResourceInterface, SortableInterface, TimestampableInterface
{
    use ResourceTrait;
    use SortableTrait;
    use TimestampableTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="options")
     */
    private $product;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductOptionValue", mappedBy="option", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"id": "ASC"})
     *
     * @Assert\Count(min=1)
     * @Assert\Valid()
     */
    private $values;

    public function __construct()
    {
        $this->values = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValues(): Collection
    {
        return $this->values;
    }

    public function addValue(ProductOptionValue $value): self
    {
        if (!$this->values->contains($value)) {
            $this->values[] = $value;
            $value->setOption($this);
        }

        return $this;
    }

    public function removeValue(ProductOptionValue $value): self
    {
        if ($this->values->contains($value)) {
            $this->values->removeElement($value);
            if ($value->getOption() === $this) {
                $value->setOption(null);
            }
        }

        return $this;
    }

    public function generateKey()
    {
        return sprintf('product_option_%s', $this->id);
    }
}
