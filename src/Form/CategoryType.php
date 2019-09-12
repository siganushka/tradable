<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Form;

use App\Entity\Category;
use App\Entity\Attribute;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CategoryType extends AbstractType
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'resource.category.name',
            ])
            ->add('sort', IntegerType::class, [
                'label' => 'app.sort',
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }

    public function onPreSetData(FormEvent $event)
    {
        $data = $event->getData();

        $choices = [];
        foreach ($this->categoryRepository->getRootCategories() as $category) {
            $choices = array_merge($choices, $category->getDescendants(true));
        }

        $parentLabel = function (Category $choice) {
            return str_repeat('——', $choice->getDepth()).$choice->getName();
        };

        $ignoreCategories = $this->getDescendants($data);
        $parentAttr = function (Category $choice) use ($ignoreCategories) {
            return ['disabled' => \in_array($choice, $ignoreCategories, true) ? true : false];
        };

        $extendAttribuites = $this->getExtendAttribuites($data);
        $attributesAttr = function (Attribute $choice, $key, $value) use ($extendAttribuites) {
            return ['disabled' => \in_array($choice, $extendAttribuites, true) ? true : false];
        };

        $event->getForm()
            ->add('parent', ChoiceType::class, [
                'choices' => $choices,
                'choice_label' => $parentLabel,
                'choice_attr' => $parentAttr,
                'choice_translation_domain' => false,
                'label' => 'resource.category.parent',
                'placeholder' => 'resource.category.root',
                'attr' => ['autofocus' => true],
            ])
            ->add('attributes', EntityType::class, [
                'label' => 'resource.category.attribute',
                'class' => Attribute::class,
                'choice_label' => 'name',
                'choice_attr' => $attributesAttr,
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    /**
     * 获取后代所有分类，包括自己.
     *
     * @param Category|null $category
     *
     * @return array
     */
    private function getDescendants(?Category $category)
    {
        if (null === $category) {
            return [];
        }

        return $category->getDescendants(true);
    }

    /**
     * 向上继承属性集.
     *
     * @param Category|null $category
     *
     * @return array
     */
    private function getExtendAttribuites(?Category $category)
    {
        if (null === $category) {
            return [];
        }

        $attribuites = [];
        foreach ($category->getAncestors() as $ancestor) {
            foreach ($ancestor->getAttributes() as $attribute) {
                $category->addAttribute($attribute);
                array_push($attribuites, $attribute);
            }
        }

        return $attribuites;
    }
}
