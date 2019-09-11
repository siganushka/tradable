<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ChooseImageType extends AbstractType
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_file', NativeFileType::class, [
                'attr' => [
                    'accept' => $options['accept'],
                    'style' => 'display: none',
                ],
            ])
            ->add('_data', HiddenType::class)
        ;

        $builder->addModelTransformer(new CallbackTransformer(
            function ($transform) {
                return ['_data' => $transform];
            },
            function ($reverseTransform) {
                return $reverseTransform['_data'];
            }
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $labelClasses = ['choose-image'];
        if (!empty($view->vars['value']['_data'])) {
            array_push($labelClasses, $options['uploaded_class']);
        }

        if ($view->vars['disabled']) {
            array_push($labelClasses, $options['disabled_class']);
        }

        $view->vars['_label_class'] = implode(' ', $labelClasses);
        $view->vars['_label_style'] = sprintf('width: %s; height: %s', $options['width'], $options['height']);

        $view->vars['loading_class'] = $options['loading_class'];
        $view->vars['disabled_class'] = $options['disabled_class'];
        $view->vars['uploaded_class'] = $options['uploaded_class'];

        $view->vars['upload_url'] = $this->urlGenerator->generate('admin_file_upload', ['channel' => $options['channel']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('channel');
        $resolver->setDefaults([
            'required' => false,
            'compound' => true,
            'width' => '100px',
            'height' => '100px',
            'accept' => 'image/*',
            'loading_class' => 'choose-image-loading',
            'disabled_class' => 'choose-image-disabled',
            'uploaded_class' => 'choose-image-uploaded',
        ]);
    }
}
