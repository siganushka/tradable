<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Validator;

use App\Entity\ProductItem;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueProductItemValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof ProductItem) {
            throw new UnexpectedTypeException($constraint, ProductItem::class);
        }

        $tokens = [];
        $curret = $value->getOptionValuesToken();

        foreach ($value->getProduct()->getItems() as $item) {
            if ($curret === $token = $item->getOptionValuesToken()) {
                if ($value->isNew()) {
                    array_push($tokens, $token);
                } else {
                    if (!$item->isEqual($value)) {
                        array_push($tokens, $token);
                    }
                }
            }
        }

        if (\count($tokens)) {
            $this->context->buildViolation($constraint->message)
                ->atPath($constraint->errorPath)
                ->addViolation();
        }
    }
}
