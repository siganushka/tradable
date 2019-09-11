<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Form;

use App\Entity\Attribute;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $extendAttribuites = $this->getExtendAttribuites($builder);
        $choiceAttr = function (Attribute $choice, $key, $value) use ($extendAttribuites) {
            return ['disabled' => \in_array($choice, $extendAttribuites, true) ? true : false];
        };

        $builder
            ->add('parent', CategoryTreeType::class, [
                'label' => 'resource.category.parent',
                'placeholder' => 'resource.category.root',
                'attr' => ['autofocus' => true],
            ])
            ->add('name', TextType::class, [
                'label' => 'resource.category.name',
            ])
            ->add('sort', IntegerType::class, [
                'label' => 'app.sort',
            ])
            ->add('attributes', EntityType::class, [
                'label' => 'resource.category.attribute',
                'class' => Attribute::class,
                'choice_label' => 'name',
                'choice_attr' => $choiceAttr,
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }

    private function getExtendAttribuites(FormBuilderInterface $builder)
    {
        $data = $builder->getData();
        if (!$data instanceof Category) {
            return [];
        }

        $attribuites = [];
        foreach ($data->getAncestors() as $ancestor) {
            foreach ($ancestor->getAttributes() as $attribute) {
                $data->addAttribute($attribute);
                array_push($attribuites, $attribute);
            }
        }

        return $attribuites;
    }
}
