<?php

namespace App\Form;

use App\Entity\ProductVariant;
use App\Utils\OptionValueUtils;
use function BenTools\CartesianProduct\cartesian_product;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
        ]);
    }

    public function onPreSetData(FormEvent $event)
    {
        $data = $event->getData();
        if (null === $data || null === $product = $data->getProduct()) {
            return;
        }

        // Used option values
        $usedOptionValues = [];
        foreach ($product->getVariants() as $variant) {
            $usedOptionValues[] = $variant->getOptionValuesIdAsString();
        }

        // Generate groups
        $groups = [];
        foreach ($product->getOptions() as $option) {
            $key = spl_object_hash($option);
            foreach ($option->getValues() as $optionValue) {
                $groups[$key][] = $optionValue;
            }
        }

        // Generate choices
        $choices = [];
        foreach (cartesian_product($groups) as $optionValues) {
            $choices[] = new ArrayCollection($optionValues);
        }

        $event->getForm()
            ->add('price', MoneyType::class, [
                'label' => 'resource.product.variant.price',
                'attr' => ['placeholder' => 'resource.product.variant.price.help'],
                'currency' => 'CNY',
                'divisor' => 100,
                'constraints' => new NotBlank(),
            ])
            ->add('inventory', IntegerType::class, [
                'label' => 'resource.product.variant.inventory',
                'attr' => ['placeholder' => 'resource.product.variant.inventory_untracked.help'],
                'constraints' => new GreaterThanOrEqual(0),
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'app.enabled',
            ])
            ->add('optionValues', ChoiceType::class, [
                'label' => 'resource.product.options',
                'placeholder' => 'app.choice',
                'choices' => $choices,
                'choice_value' => function (?Collection $choice) {
                    return (new OptionValueUtils($choice))->getIdAsString();
                },
                'choice_label' => function (?Collection $choice, $key, $value) use ($usedOptionValues) {
                    $label = (new OptionValueUtils($choice))->getNameAsString();
                    if (\in_array($value, $usedOptionValues)) {
                        $label .= ' (√)';
                    }

                    return $label;
                },
                'choice_attr' => function (?Collection $choice, $key, $value) use ($usedOptionValues) {
                    return ['disabled' => \in_array($value, $usedOptionValues)];
                },
                'choice_translation_domain' => false,
            ])
        ;
    }
}
