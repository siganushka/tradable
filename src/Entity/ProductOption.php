<?php

namespace App\Entity;

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
     * @ORM\Column(type="json")
     */
    private $choices = [];

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

    public function getChoices(): ?array
    {
        return $this->choices;
    }

    public function setChoices(array $choices): self
    {
        $this->choices = $this->filterChoices($choices);

        return $this;
    }

    private function filterChoices(array $choices)
    {
        $choices = array_filter(array_unique(array_map('trim', $choices)));

        $newChoices = [];
        foreach ($choices as $key => $value) {
            if (!\is_int($key)) {
                $newChoices[$key] = $value;
                continue;
            }

            $newKey = uniqid();
            $newChoices[$newKey] = $value;
        }

        return $newChoices;
    }
}
