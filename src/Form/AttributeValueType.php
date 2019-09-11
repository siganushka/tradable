<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Form;

use App\Registry\AttributeTypeRegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributeValueType extends AbstractType
{
    private $registry;

    public function __construct(AttributeTypeRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['attributes'] as $attribute) {
            $type = $this->registry->get($attribute->getType());

            $formType = $type->getConfigurationType();
            $formOptions = $type->getConfigurationOptions($attribute);

            $builder->add($attribute->getId(), $formType, $formOptions);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('attributes');
        $resolver->setAllowedTypes('attributes', 'App\Entity\Attribute[]');
    }
}
