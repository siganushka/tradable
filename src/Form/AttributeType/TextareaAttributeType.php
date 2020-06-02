<?php

namespace App\Form\AttributeType;

use App\Entity\Attribute;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TextareaAttributeType extends AttributeType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rows', IntegerType::class, [
                'label' => 'resource.attribute.configuration.rows',
            ])
            ->add('min_length', IntegerType::class, [
                'label' => 'resource.attribute.configuration.min_length',
            ])
            ->add('max_length', IntegerType::class, [
                'label' => 'resource.attribute.configuration.max_length',
            ])
            ->add('required', CheckboxType::class, [
                'label' => 'app.required',
            ])
        ;
    }

    public function getConfigurationType(): string
    {
        return TextareaType::class;
    }

    public function getConfigurationOptions(Attribute $attribute): array
    {
        $configuration = $attribute->getConfiguration();

        $options = [
            'label' => $attribute->getName(),
            'translation_domain' => false,
        ];

        if (isset($configuration['required']) && $configuration['required']) {
            $options['constraints'][] = new NotBlank();
        }

        if (isset($configuration['rows'])) {
            $options['attr']['rows'] = $configuration['rows'];
        }

        $lengthArgs = [];
        if (isset($configuration['min_length'])) {
            $lengthArgs['min'] = $configuration['min_length'];
        }

        if (isset($configuration['max_length'])) {
            $lengthArgs['max'] = $configuration['max_length'];
        }

        if (\count($lengthArgs)) {
            $options['constraints'][] = new Length($lengthArgs);
        }

        return $options;
    }

    public function getAlias(): string
    {
        return 'textarea';
    }
}
