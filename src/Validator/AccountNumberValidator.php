<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AccountNumberValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $wrongAccountNumber = false;
        /* @var $constraint \App\Validator\AccountNumber */

        if (null === $value || '' === $value) {
            return;
        }
        $arrayValues = explode(' ', $value);
        if (7 !== count($arrayValues)) {
            $wrongAccountNumber = true;
        }

        foreach ($arrayValues as $key => $value) {
            if (strlen((int) $value) != strlen($value) || ((0 == $key && 2 != strlen($value)) || (0 != $key && 4 != strlen($value)))) {
                $wrongAccountNumber = true;
            }
        }

        if ($wrongAccountNumber) {
            $this->context->buildViolation($constraint->message)
            ->addViolation();
        }
    }
}
