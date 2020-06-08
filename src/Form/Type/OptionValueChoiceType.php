<?php

namespace App\Form\Type;

use App\Entity\Product;
use App\Form\ChoiceList\OptionValueChoiceLoader;
use App\Form\ChoiceList\OptionValueChoiceUtils;
use App\Utils\OptionValueUtils;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OptionValueChoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // $builder->addModelTransformer(new CallbackTransformer(
        //     function ($transform) {
        //         dump((new OptionValueUtils($transform))->getIdAsString());
        //         return (new OptionValueUtils($transform))->getIdAsString();
        //     },
        //     function ($reverseTransform) use ($options) {
        //         dump($reverseTransform);
        //         return (new OptionValueChoiceUtils($options['product']))->loadChoiceByKey($reverseTransform);
        //     }
        // ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $choiceLoader = function (Options $options) {
            if (null === $options['choices']) {
                return new OptionValueChoiceLoader($options['product']);
            }
        };

        $resolver->setDefaults([
            'choices' => null,
            'choice_loader' => $choiceLoader,
            'choice_value' => function (?Collection $choice) {
                return (new OptionValueUtils($choice))->getIdAsString();
            },
            'choice_label' => function (Collection $choice, $key, $value) use ($usedOptionValues) {
                $label = (new OptionValueUtils($choice))->getNameAsString();
                if (\in_array($value, $usedOptionValues)) {
                    $label .= ' (√)';
                }

                return $label;
            },
            'choice_attr' => function (Collection $choice, $key, $value) use ($usedOptionValues) {
                return ['disabled' => \in_array($value, $usedOptionValues)];
            },
            'choice_translation_domain' => false,
        ]);

        $resolver->setRequired('product');
        $resolver->setAllowedTypes('product', ['null', Product::class]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
