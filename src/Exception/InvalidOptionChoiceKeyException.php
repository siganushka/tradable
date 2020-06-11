<?php

namespace App\Exception;

use App\Entity\ProductVariant;

class InvalidOptionChoiceKeyException extends \InvalidArgumentException
{
    private $variant;

    public function __construct(ProductVariant $variant)
    {
        $this->variant = $variant;

        parent::__construct(sprintf('Invalid option choice key: %s', $variant->getOptionChoiceKey()));
    }

    public function getVariant()
    {
        return $this->variant;
    }
}
