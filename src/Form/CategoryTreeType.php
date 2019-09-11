<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Form;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryTreeType extends AbstractType
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $choices = [];
        foreach ($this->categoryRepository->getRootCategories() as $category) {
            $choices = array_merge($choices, $category->getDescendants(true));
        }

        $choicesLabel = function (Category $choice, $key, $value) {
            return str_repeat('——', $choice->getDepth()).$choice->getName();
        };

        $resolver->setDefaults([
            'choices' => $choices,
            'choice_label' => $choicesLabel,
            'choice_translation_domain' => false,
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
