<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Registry;

use App\Exception\AttributeUnsupportedTypeException;
use App\Form\AttributeType\AttributeTypeInterface;

class AttributeTypeRegistry implements AttributeTypeRegistryInterface
{
    private $attributeTypes = [];

    public function register(string $alias, AttributeTypeInterface $type): AttributeTypeRegistryInterface
    {
        $this->attributeTypes[$alias] = $type;

        return $this;
    }

    public function has(string $alias): bool
    {
        return \array_key_exists($alias, $this->attributeTypes);
    }

    public function get(string $alias): AttributeTypeInterface
    {
        if (!$this->has($alias)) {
            throw new AttributeUnsupportedTypeException($alias);
        }

        return $this->attributeTypes[$alias];
    }

    public function all(): iterable
    {
        return $this->attributeTypes;
    }
}
