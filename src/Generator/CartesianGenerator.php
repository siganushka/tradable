<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Generator;

class CartesianGenerator implements \IteratorAggregate, \Countable
{
    private $set = [];
    private $isRecursiveStep = false;
    private $count;

    public function __construct(array $set)
    {
        $this->set = $set;
    }

    public function getIterator()
    {
        if (!empty($this->set)) {
            $keys = array_keys($this->set);
            $key = end($keys);
            $subset = array_pop($this->set);
            $this->validate($subset, $key);
            foreach (self::subset($this->set) as $product) {
                foreach ($subset as $value) {
                    if ($value instanceof \Closure) {
                        yield $product + [$key => $value($product)];
                    } else {
                        yield $product + [$key => $value];
                    }
                }
            }
        } else {
            if (true === $this->isRecursiveStep) {
                yield [];
            }
        }
    }

    private function validate($subset, $key)
    {
        if (!\is_array($subset) || empty($subset)) {
            throw new \InvalidArgumentException(sprintf('Key "%s" should return a non-empty array', $key));
        }
    }

    private static function subset(array $subset)
    {
        $product = new self($subset);
        $product->isRecursiveStep = true;

        return $product;
    }

    public function asArray()
    {
        return iterator_to_array($this);
    }

    public function count()
    {
        if (null === $this->count) {
            $this->count = (int) array_product(array_map(function ($subset, $key) {
                $this->validate($subset, $key);

                return \count($subset);
            }, $this->set, array_keys($this->set)));
        }

        return $this->count;
    }
}
