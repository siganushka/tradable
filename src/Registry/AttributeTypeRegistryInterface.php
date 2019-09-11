<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Registry;

use App\Form\AttributeType\AttributeTypeInterface;

interface AttributeTypeRegistryInterface
{
    public function register(string $alias, AttributeTypeInterface $type): self;

    public function has(string $alias): bool;

    public function get(string $alias): AttributeTypeInterface;

    public function all(): iterable;
}
