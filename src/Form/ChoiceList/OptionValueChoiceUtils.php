<?php

namespace App\Form\ChoiceList;

use App\Entity\Product;
use App\Utils\OptionValueUtils;
use function BenTools\CartesianProduct\cartesian_product;
use Doctrine\Common\Collections\ArrayCollection;

class OptionValueChoiceUtils
{
    private $names = [];
    private $choices = [];

    public function __construct(Product $product)
    {
        $groups = [];
        foreach ($product->getOptions() as $option) {
            $key = spl_object_hash($option);
            foreach ($option->getValues() as $optionValue) {
                $groups[$key][] = $optionValue;
            }
        }

        $choices = [];
        foreach (cartesian_product($groups) as $optionValues) {
            $collection = new ArrayCollection(array_values($optionValues));
            $utils = new OptionValueUtils($collection);

            $idAsString = $utils->getIdAsString();
            $nameAsString = $utils->getNameAsString();

            $this->names[$idAsString] = new OptionValueChoice($idAsString, $nameAsString, $collection);

            $this->choices[$idAsString] = $collection;
        }
    }

    public function getNames()
    {
        return $this->names;
    }

    public function getChoices()
    {
        return $this->choices;
    }

    public function loadChoiceByKey(string $id)
    {
        return $this->choices[$id];
    }
}
