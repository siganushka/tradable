<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductOptionValueCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['product']->getOptions() as $option) {
            $builder->add($option->generateKey(), ChoiceType::class, [
                'label' => $option->getName(),
                'error_bubbling' => false,
                'translation_domain' => false,
                'placeholder' => '请选择',
                'choices' => $option->getValues(),
                'choice_value' => 'id',
                'choice_label' => 'name',
                'constraints' => new NotBlank(),
            ]);
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('product');
        $resolver->setAllowedTypes('product', Product::class);
    }

    public function onPreSetData(FormEvent $event)
    {
        $indexedData = [];
        foreach ($event->getData() as $optionValue) {
            $indexedData[$optionValue->getOption()->generateKey()] = $optionValue;
        }

        $event->setData($indexedData);
    }
}
