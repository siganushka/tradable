<?php

namespace App\Form\DataTransformer;

use App\Entity\ProductOptionValue;
use Symfony\Component\Form\DataTransformerInterface;
use function Symfony\Component\String\u;

class OptionValueToStringTransformer implements DataTransformerInterface
{
    private $delimiter;

    public function __construct(string $delimiter = '/')
    {
        $this->delimiter = $delimiter;
    }

    public function transform($value): string
    {
        $array = [];
        foreach ($value as $value) {
            if ($value instanceof ProductOptionValue) {
                array_push($array, $value->getName());
            }
        }

        return  implode($this->delimiter, $array);
    }

    public function reverseTransform($value): array
    {
        if (null === $value || u($value)->isEmpty()) {
            return [];
        }

        $newNames = array_filter(array_unique(array_map('trim', u($value)->split($this->delimiter))));

        // introduced in PHP 7.4
        // @see https://www.php.net/manual/en/functions.arrow.php
        return array_map(fn ($item) => new ProductOptionValue($item), $newNames);
    }
}
