<?php

namespace App\Form\AttributeType;

use App\Entity\Attribute;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;

class CheckboxAttributeType extends AttributeType
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
            ->add('min_count', IntegerType::class, [
                'label' => 'resource.attribute.configuration.min_count',
            ])
            ->add('max_count', IntegerType::class, [
                'label' => 'resource.attribute.configuration.max_count',
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
            'translation_domain' => false,
            'choices' => array_flip($configuration['choices']),
            'multiple' => true,
            'expanded' => true,
        ];

        $countArgs = [];
        if (isset($configuration['min_count'])) {
            $countArgs['min'] = $configuration['min_count'];
        }

        if (isset($configuration['max_count'])) {
            $countArgs['max'] = $configuration['max_count'];
        }

        if (\count($countArgs)) {
            $options['constraints'] = new Count($countArgs);
        }

        return $options;
    }

    public function getAlias(): string
    {
        return 'checkbox';
    }
}
