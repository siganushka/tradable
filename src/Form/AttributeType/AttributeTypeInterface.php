<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Form\AttributeType;

use App\Entity\Attribute;
use Siganushka\GenericBundle\Registry\AliasableServiceInterface;
use Symfony\Component\Form\FormTypeInterface;

interface AttributeTypeInterface extends FormTypeInterface, AliasableServiceInterface
{
    public function getConfigurationType(): string;

    public function getConfigurationOptions(Attribute $attribute): array;
}
