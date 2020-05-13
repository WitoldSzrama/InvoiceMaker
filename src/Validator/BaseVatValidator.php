<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BaseVatValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\BaseVat */
        $validate = null;
        if (null === $value || '' === $value) {
            return;
        }
        $baseVatArray = explode(', ', $value);
        foreach ($baseVatArray as $vat) {
            if(!is_numeric($vat) || !is_integer((int)$vat) || !((int)$vat >= 0 && (int)$vat <= 100)) {
                $validate = $validate . ' ' . $vat;
            }
        }
        if($validate) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $validate)
            ->setTranslationDomain('message')
            ->addViolation();
        }
    }
}
