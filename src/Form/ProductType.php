<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'resource.product.name',
                'help' => 'resource.product.name.help',
            ])
            ->add('unit', TextType::class, [
                'label' => 'resource.product.unit',
                'help' => 'resource.product.unit.help',
            ])
            ->add('options', CollectionType::class, [
                'label' => 'resource.product.option',
                'help' => 'resource.product.option.help',
                'help_html' => true,
                'entry_type' => ProductOptionType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'error_bubbling' => false,
                'by_reference' => false,
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'app.enabled',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
