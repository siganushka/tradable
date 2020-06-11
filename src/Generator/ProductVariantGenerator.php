<?php

namespace App\Generator;

use App\Entity\Product;
use App\Utils\OptionValueUtils;
use function BenTools\CartesianProduct\cartesian_product;
use Doctrine\Common\Collections\ArrayCollection;

class ProductVariantGenerator implements ProductVariantGeneratorInterface
{
    protected $optionNames = [];
    protected $optionValues = [];

    public function __construct(Product $product)
    {
        // Generate groups
        $groups = [];
        foreach ($product->getOptions() as $option) {
            $key = spl_object_hash($option);
            foreach ($option->getValues() as $optionValue) {
                $groups[$key][] = $optionValue;
            }
        }

        // Generate choices
        $choices = [];
        foreach (cartesian_product($groups) as $optionValues) {
            $collection = new ArrayCollection($optionValues);

            $utils = new OptionValueUtils($collection);

            $ids = $utils->getIds();

            dd($ids);

            $choices[] = array_values($optionValues);
        }

        dd($choices);
    }
}
