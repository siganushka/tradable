<?php

namespace App\Generator;

interface ProductVariantGeneratorInterface extends \ArrayAccess, \IteratorAggregate, \Countable
{
    public function toArray(): array;
}
