<?php

namespace App\Form;

use App\Entity\ProductVariant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductVariantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => ProductVariant::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }

    public function onPreSetData(FormEvent $event)
    {
        $data = $event->getData();
        if (null === $data || null === $product = $data->getProduct()) {
            return;
        }

        $event->getForm()
            ->add('price', MoneyType::class, [
                'label' => 'resource.product.variant.price',
                'currency' => 'CNY',
                'divisor' => 100,
                'constraints' => new NotBlank(),
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'resource.product.variant.quantity',
                'attr' => ['placeholder' => 'resource.product.variant.quantity_untracked'],
                'constraints' => new GreaterThanOrEqual(0),
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'app.enabled',
            ])
            ->add('optionValues', ProductOptionValueCollectionType::class, [
                'label' => 'resource.product.option',
                'error_bubbling' => false,
                'product' => $product,
            ])
        ;
    }
}
