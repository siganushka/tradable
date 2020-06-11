<?php

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

        $optionChoiceKeys = [];
        foreach ($value->getProduct()->getVariants() as $variant) {
            if ($value->getOptionChoiceKey() === $key = $variant->getOptionChoiceKey()) {
                if ($value->isNew()) {
                    array_push($optionChoiceKeys, $key);
                } else {
                    if (!$variant->isEqualTo($value)) {
                        array_push($optionChoiceKeys, $key);
                    }
                }
            }
        }

        if (\count($optionChoiceKeys)) {
            $this->context->buildViolation($constraint->message)
                ->atPath($constraint->errorPath)
                ->addViolation();
        }
    }
}
