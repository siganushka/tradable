<?php

namespace App\Generator;

use App\Entity\Product;
use function BenTools\CartesianProduct\cartesian_product;

class ProductVariantGenerator implements ProductVariantGeneratorInterface
{
    protected $items = [];

    public function __construct(Product $product)
    {
        // Generate groups
        $groups = [];
        foreach ($product->getOptions() as $option) {
            $choices = $option->getChoices();
            ksort($choices);

            $groupKey = spl_object_hash($option);
            foreach ($choices as $key => $value) {
                $groups[$groupKey][] = [$key, $value];
            }
        }

        // Generate cartesians
        foreach (cartesian_product($groups) as $optionChoices) {
            $key = implode('-', array_map(fn ($item) => $item[0], $optionChoices));
            $value = implode('/', array_map(fn ($item) => $item[1], $optionChoices));

            $this->items[$key] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->items[$offset]) ? $this->items[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        throw new \LogicException('Setting options via array access is not supported.');
    }

    public function offsetUnset($offset)
    {
        throw new \LogicException('Removing options via array access is not supported.');
    }

    public function toArray(): array
    {
        return $this->items;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }

    public function count(): int
    {
        return \count($this->items);
    }
}
