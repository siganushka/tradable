<?php

namespace App\Form;

use App\Entity\ProductVariant;
use App\Generator\ProductVariantGenerator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        ]);
    }

    public function onPreSetData(FormEvent $event)
    {
        $data = $event->getData();
        if (null === $data || null === $product = $data->getProduct()) {
            return;
        }

        $usedOptionChoiceKeys = [];
        foreach ($product->getVariants() as $variant) {
            $usedOptionChoiceKeys[] = $variant->getOptionChoiceKey();
        }

        $choices = new ProductVariantGenerator($product);
        $choices = array_flip($choices->toArray());

        $event->getForm()
            ->add('price', MoneyType::class, [
                'label' => 'resource.product.variant.price',
                'attr' => ['placeholder' => 'resource.product.variant.price.help'],
                'currency' => 'CNY',
                'divisor' => 100,
            ])
            ->add('inventory', IntegerType::class, [
                'label' => 'resource.product.variant.inventory',
                'attr' => ['placeholder' => 'resource.product.variant.inventory_untracked.help'],
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'app.enabled',
            ])
            ->add('optionChoiceKey', ChoiceType::class, [
                'label' => 'resource.product.options',
                'placeholder' => 'app.choice',
                'choices' => $choices,
                'choice_label' => function ($choice, $key, $value) use ($usedOptionChoiceKeys) {
                    if (\in_array($value, $usedOptionChoiceKeys)) {
                        $key .= ' (√)';
                    }

                    return $key;
                },
                'choice_attr' => function ($choice, $key, $value) use ($usedOptionChoiceKeys) {
                    return ['disabled' => \in_array($value, $usedOptionChoiceKeys)];
                },
                'choice_translation_domain' => false,
                'disabled' => !$data->isNew(),
            ])
        ;
    }
}
