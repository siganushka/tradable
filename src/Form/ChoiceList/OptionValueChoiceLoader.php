<?php

namespace App\Form\ChoiceList;

use App\Entity\Product;
use function BenTools\CartesianProduct\cartesian_product;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\ChoiceList\Loader\AbstractChoiceLoader;

class OptionValueChoiceLoader extends AbstractChoiceLoader
{
    private $choices = [];

    public function __construct(Product $product)
    {
        // $usedOptionValues = [];
        // foreach ($this->product->getVariants() as $variant) {
        //     $usedOptionValues[] = $variant->getOptionValuesIdAsString();
        // }

        $groups = [];
        foreach ($product->getOptions() as $option) {
            $key = spl_object_hash($option);
            foreach ($option->getValues() as $optionValue) {
                $groups[$key][] = $optionValue;
            }
        }

        foreach (cartesian_product($groups) as $optionValues) {
            $this->choices = new ArrayCollection(array_values($optionValues));
        }
    }

    protected function loadChoices(): iterable
    {
        return $this->choices;
    }

    protected function doLoadChoicesForValues(array $values, ?callable $value): array
    {
        $result = [];
        foreach ($values as $key => $value) {
            dd($key, $value);
        }

        return $result;
    }

    protected function doLoadValuesForChoices(array $choices): array
    {
        $values = [];
        foreach ($choices as $i => $object) {
            if ($object instanceof OptionValueChoice) {
                $values[$i] = $this->idReader->getIdValue($object);
            }
        }

        return $values;
    }
}
