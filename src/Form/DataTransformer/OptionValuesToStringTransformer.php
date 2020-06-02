<?php

namespace App\Form\DataTransformer;

use App\Entity\ProductOptionValue;
use App\Repository\ProductOptionValueRepository;
use Symfony\Component\Form\DataTransformerInterface;
use function Symfony\Component\String\u;

class OptionValuesToStringTransformer implements DataTransformerInterface
{
    private $repository;
    private $delimiter;

    public function __construct(ProductOptionValueRepository $repository, $delimiter = ',')
    {
        $this->repository = $repository;
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

        $names = array_filter(array_unique(array_map('trim', u($value)->split($this->delimiter))));

        $result = $this->repository->findBy(['name' => $names]);

        // introduced in PHP 7.4
        // @see https://www.php.net/manual/en/functions.arrow.php
        $namesAsArray = array_map(fn ($item) => $item->getName(), $result);

        foreach (array_diff($names, $namesAsArray) as $value) {
            $result[] = new ProductOptionValue($value);
        }

        return $result;
    }
}
