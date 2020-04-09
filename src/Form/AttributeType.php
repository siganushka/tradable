<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Form;

use App\Entity\Attribute;
use App\Registry\AttributeTypeRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AttributeType extends AbstractType
{
    private $registry;

    public function __construct(AttributeTypeRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', TextType::class, [
                'label' => 'resource.attribute.type',
                'attr' => ['readonly' => 'readonly'],
            ])
            ->add('name', TextType::class, [
                'label' => 'resource.attribute.name',
                'attr' => ['autofocus' => true],
                'constraints' => new NotBlank(),
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Attribute::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }

    public function onPreSetData(FormEvent $event)
    {
        $data = $event->getData();
        if (null === $data || null === $data->getType()) {
            return;
        }

        $type = $this->registry->get($data->getType());
        $event->getForm()->add('configuration', \get_class($type), [
            'label' => 'resource.attribute.configuration',
        ]);
    }
}
