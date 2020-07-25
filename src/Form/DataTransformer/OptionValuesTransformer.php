<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use function Symfony\Component\String\u;

class OptionValuesTransformer implements DataTransformerInterface
{
    const DELIMITER = '/';

    public function transform($value): string
    {
        if (null === $value) {
            $value = [];
        }

        return implode(static::DELIMITER, $value);
    }

    public function reverseTransform($value): array
    {
        if (null === $value) {
            return [];
        }

        $value = u($value)->split(static::DELIMITER);
        $value = array_map('trim', $value);
        $value = array_unique($value);

        return array_filter($value);
    }
}
