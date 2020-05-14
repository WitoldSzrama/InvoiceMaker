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
        if (count($arrayValues) !== 7) {
            $wrongAccountNumber = true;
        }

        foreach ($arrayValues as $key => $value) {

            if (strlen((int)$value) != strlen($value) || (($key == 0 && strlen($value) != 2) || ($key != 0 && strlen($value) != 4))){
                $wrongAccountNumber = true;
            }

        }
        
        if ($wrongAccountNumber) {
            $this->context->buildViolation($constraint->message)
            ->addViolation();
        }
    }
}
