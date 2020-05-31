<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'resource.product.name',
            ])
            ->add('unit', TextType::class, [
                'label' => 'resource.product.unit',
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'app.enabled',
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }

    public function onPreSetData(FormEvent $event)
    {
        $data = $event->getData();
        if (null === $data || null === $category = $data->getCategory()) {
            return;
        }

        $attribuites = [];
        foreach ($category->getAncestors(true) as $ancestor) {
            foreach ($ancestor->getAttributes() as $attribute) {
                array_push($attribuites, $attribute);
            }
        }

        if (empty($attribuites)) {
            return;
        }

        $event->getForm()->add('attributeValues', AttributeValueType::class, [
            'label' => 'resource.product.attribute',
            'attributes' => $attribuites,
        ]);
    }
}
