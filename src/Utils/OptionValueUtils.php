<?php

namespace App\Utils;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class OptionValueUtils
{
    private $optionValues;

    public function __construct(?Collection $optionValues)
    {
        // No collection for choice placeholder
        if (!$optionValues) {
            $optionValues = new ArrayCollection();
        }

        $iterator = $optionValues->getIterator();
        $iterator->uasort(function ($a, $b) {
            return ($a->getId() > $b->getId()) ? 1 : -1;
        });

        $this->optionValues = iterator_to_array($iterator);
    }

    public function getIds()
    {
        return implode('/', array_map(fn ($item) => $item->getId(), $this->optionValues));
    }

    public function getNames()
    {
        return implode('/', array_map(fn ($item) => $item->getName(), $this->optionValues));
    }
}
