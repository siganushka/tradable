<?php

namespace App\Entity;

use App\Exception\CategoryDescendantConflictException;
use App\Exception\CategoryParentConflictException;
use App\Tree\NodeInterface;
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
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class Category implements ResourceInterface, SortableInterface, TimestampableInterface, NodeInterface
{
    use ResourceTrait;
    use SortableTrait;
    use TimestampableTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="children")
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Category", mappedBy="parent", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"sort": "ASC", "id": "DESC"})
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
     */
    private $children;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=64)
     */
    private $name;

    /**
     * @ORM\Column(type="smallint")
     *
     * @Assert\GreaterThanOrEqual(-32768)
     * @Assert\LessThanOrEqual(32767)
     */
    private $depth;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="category")
     * @ORM\OrderBy({"createdAt": "ASC"})
     */
    private $products;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Attribute", inversedBy="categories")
     * @ORM\OrderBy({"sort": "ASC"})
     */
    private $attributes;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->attributes = new ArrayCollection();
    }

    public function getParent(): ?NodeInterface
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        if ($parent && $parent->isEqualTo($this) && !$parent->isNew()) {
            throw new CategoryParentConflictException($this, $parent);
        }

        if ($parent && \in_array($parent, $this->getDescendants(), true)) {
            throw new CategoryDescendantConflictException($this, $parent);
        }

        $this->parent = $parent;

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

    public function getDepth(): int
    {
        if (null === $this->depth) {
            $this->recalculateDepth();
        }

        return $this->depth;
    }

    public function setDepth(int $depth): self
    {
        $this->depth = $depth;

        return $this;
    }

    public function recalculateDepth(): ?int
    {
        if ($this->isRoot()) {
            return $this->depth = 0;
        }

        return $this->depth = $this->getParent()->getDepth() + 1;
    }

    public function getChildren(): iterable
    {
        return $this->children;
    }

    public function setChildren(iterable $children): self
    {
        $this->children = $children;

        return $this;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function getAncestors(bool $includeSelf = false): iterable
    {
        $parents = $includeSelf ? [$this] : [];
        $node = $this;

        while ($parent = $node->getParent()) {
            array_unshift($parents, $parent);
            $node = $parent;
        }

        return $parents;
    }

    public function getSiblings(bool $includeSelf = false): iterable
    {
        if ($this->isRoot()) {
            return [];
        }

        $siblings = [];
        foreach ($this->getParent()->getChildren() as $child) {
            if ($includeSelf || !$this->isEqualTo($child)) {
                $siblings[] = $child;
            }
        }

        return $siblings;
    }

    public function getDescendants(bool $includeSelf = false): iterable
    {
        $descendants = $includeSelf ? [$this] : [];
        foreach ($this->children as $child) {
            $descendants[] = $child;
            if (!$child->isLeaf()) {
                $descendants = array_merge($descendants, $child->getDescendants());
            }
        }

        return $descendants;
    }

    public function getRoot(): NodeInterface
    {
        $node = $this;

        while ($parent = $node->getParent()) {
            $node = $parent;
        }

        return $node;
    }

    public function isRoot(): bool
    {
        return null === $this->getParent();
    }

    public function isLeaf(): bool
    {
        return 0 === \count($this->children);
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }

    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    public function addAttribute(Attribute $attribute): self
    {
        if (!$this->attributes->contains($attribute)) {
            $this->attributes[] = $attribute;
        }

        return $this;
    }

    public function removeAttribute(Attribute $attribute): self
    {
        if ($this->attributes->contains($attribute)) {
            $this->attributes->removeElement($attribute);
        }

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function lifecycleCallbacks()
    {
        $this->depth = $this->recalculateDepth();
    }
}
