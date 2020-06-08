<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'resource.product.name',
                'attr' => ['placeholder' => 'resource.product.name.help'],
            ])
            ->add('unit', TextType::class, [
                'label' => 'resource.product.unit',
                'attr' => ['placeholder' => 'resource.product.unit.help'],
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'app.enabled',
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }

    public function onPreSetData(FormEvent $event)
    {
        $event->getForm()->add('options', CollectionType::class, [
            'label' => 'resource.product.options',
            'help' => 'resource.product.options.help',
            'help_html' => true,
            'entry_type' => ProductOptionType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'error_bubbling' => false,
            'by_reference' => false,
            'disabled' => !$event->getData()->isNew(),
        ]);
    }
}
