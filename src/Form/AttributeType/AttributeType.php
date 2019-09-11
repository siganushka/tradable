<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Form\AttributeType;

use Symfony\Component\Form\AbstractType;

abstract class AttributeType extends AbstractType implements AttributeTypeInterface
{
    public function getLabel(): string
    {
        return sprintf('resource.attribute.type.%s', $this->getBlockPrefix());
    }
}
