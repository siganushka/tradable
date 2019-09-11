<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Exception;

class AttributeUnsupportedTypeException extends \InvalidArgumentException
{
    private $type;

    public function __construct(string $type)
    {
        $this->type = $type;

        parent::__construct(sprintf('The attribute type "%s" is not supported', $type));
    }

    public function getType()
    {
        return $this->type;
    }
}
