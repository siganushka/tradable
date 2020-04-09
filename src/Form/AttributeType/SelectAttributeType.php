<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Form\AttributeType;

use App\Entity\Attribute;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;

class SelectAttributeType extends AttributeType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('choices', CollectionType::class, [
                'label' => 'resource.attribute.configuration.choice',
                'entry_type' => TextType::class,
                'entry_options' => [
                    'label' => false,
                    'constraints' => new NotBlank(),
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'error_bubbling' => false,
                'by_reference' => false,
                'constraints' => new Count(['min' => 1]),
            ])
            ->add('required', CheckboxType::class, [
                'label' => 'app.required',
            ])
        ;
    }

    public function getConfigurationType(): string
    {
        return ChoiceType::class;
    }

    public function getConfigurationOptions(Attribute $attribute): array
    {
        $configuration = $attribute->getConfiguration();

        $options = [
            'label' => $attribute->getName(),
            'choices' => array_flip($configuration['choices']),
        ];

        if (isset($configuration['required']) && $configuration['required']) {
            $options['constraints'] = new NotBlank();
        }

        return $options;
    }

    public function getAlias(): string
    {
        return 'select';
    }
}
