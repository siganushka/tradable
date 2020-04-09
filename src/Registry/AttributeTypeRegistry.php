<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Registry;

use App\Form\AttributeType\AttributeTypeInterface;
use Siganushka\GenericBundle\Registry\AbstractRegistry;

class AttributeTypeRegistry extends AbstractRegistry
{
    public function __construct()
    {
        parent::__construct(AttributeTypeInterface::class);
    }
}
