<?php

namespace App\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DisableHtml5ValidateTypeExtension extends AbstractTypeExtension
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }
}
