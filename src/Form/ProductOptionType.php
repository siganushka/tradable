<?php

namespace App\Form;

use App\Entity\ProductOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Symfony\Component\String\u;

class ProductOptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'resource.product.options.name',
                'attr' => [
                    'autofocus' => true,
                    'placeholder' => 'resource.product.options.name.help',
                ],
            ])
            ->add('choices', TextType::class, [
                'label' => 'resource.product.options.choices',
                'attr' => ['placeholder' => 'resource.product.options.choices.help'],
            ])
        ;

        $builder->get('choices')
            ->addModelTransformer(new CallbackTransformer(
                function ($choiceAsArray) {
                    return $choiceAsArray ? implode('/', $choiceAsArray) : '';
                },
                function ($choiceAsString) {
                    return array_filter(array_unique(array_map('trim', u($choiceAsString)->split('/'))));
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductOption::class,
        ]);
    }
}
