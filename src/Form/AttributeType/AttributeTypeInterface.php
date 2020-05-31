<?php

namespace App\Form\AttributeType;

use App\Entity\Attribute;
use Siganushka\GenericBundle\Registry\AliasableInterface;
use Symfony\Component\Form\FormTypeInterface;

interface AttributeTypeInterface extends FormTypeInterface, AliasableInterface
{
    public function getConfigurationType(): string;

    public function getConfigurationOptions(Attribute $attribute): array;
}
