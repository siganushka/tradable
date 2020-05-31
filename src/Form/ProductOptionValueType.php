<?php

namespace App\Form;

use App\Entity\ProductOptionValue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductOptionValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['placeholder' => 'resource.product.option.value.name'],
            ])
            ->add('sort', TextType::class, [
                'attr' => ['placeholder' => 'app.sort'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductOptionValue::class,
            'error_bubbling' => false,
        ]);
    }
}
