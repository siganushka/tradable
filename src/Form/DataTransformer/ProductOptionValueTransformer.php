<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Form\DataTransformer;

use App\Entity\ProductOptionValue;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ProductOptionValueTransformer implements DataTransformerInterface
{
    public function transform($collection)
    {
        if (null === $collection) {
            return [];
        }

        if (\is_array($collection)) {
            return $collection;
        }

        $splitValue = [];
        foreach ($collection as $key => $value) {
            if (!$value instanceof ProductOptionValue) {
                throw new TransformationFailedException(sprintf('Expected a %s object.', ProductOptionValue::class));
            }

            $splitValue[$key] = $value;
        }

        return $splitValue;
    }

    public function reverseTransform($value)
    {
    }
}
