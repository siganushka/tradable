<?php

namespace App\Utils;

use Doctrine\Common\Collections\Collection;

class OptionValueUtils
{
    private $optionValues;

    public function __construct(Collection $optionValues)
    {
        $iterator = $optionValues->getIterator();
        $iterator->uasort(function ($a, $b) {
            return ($a->getId() > $b->getId()) ? 1 : -1;
        });

        $this->optionValues = iterator_to_array($iterator);
    }

    public function getIdAsString()
    {
        return implode('/', array_map(fn ($item) => $item->getId(), $this->optionValues));
    }

    public function getNameAsString()
    {
        return implode('/', array_map(fn ($item) => $item->getName(), $this->optionValues));
    }
}
