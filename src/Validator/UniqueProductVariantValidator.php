<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Validator;

use App\Entity\ProductVariant;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueProductVariantValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof ProductVariant) {
            throw new UnexpectedTypeException($constraint, ProductVariant::class);
        }

        $tokens = [];
        $curret = $value->getOptionValuesToken();

        foreach ($value->getProduct()->getVariants() as $variant) {
            if ($curret === $token = $variant->getOptionValuesToken()) {
                if ($value->isNew()) {
                    array_push($tokens, $token);
                } else {
                    if (!$variant->isEqualTo($value)) {
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
