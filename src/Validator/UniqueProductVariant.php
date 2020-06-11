<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueProductVariant extends Constraint
{
    public $message = 'This value is already used.';
    public $errorPath = 'optionChoiceKey';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
