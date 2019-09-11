<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Form\AttributeType;

use App\Entity\Attribute;
use Symfony\Component\Form\FormTypeInterface;

interface AttributeTypeInterface extends FormTypeInterface
{
    public function getLabel(): string;

    public function getConfigurationType(): string;

    public function getConfigurationOptions(Attribute $attribute): array;
}
