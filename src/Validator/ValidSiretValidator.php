<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidSiretValidator extends ConstraintValidator
{
    public const SIRET_LENGTH = 14;

    public function validate($siret, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\ValidSiret */

        if (null === $siret || '' === $siret) {
            return;
        }

        $error = false;

        if (!is_numeric($siret)) {
            $constraint->message = 'Le SIRET ne doit contenir que des chiffres';
            $this->context->buildViolation($constraint->message)
                ->addViolation();

            $error = true;
        }

        if (!$error && strlen($siret) != 14) {
            $constraint->message = 'Le SIRET doit contenir 14 chiffres';
            $this->context->buildViolation($constraint->message)
                ->addViolation();

            $error = true;
        }



        $sum = 0;

        if (!$error) {
            for ($i = 0; $i < 14; $i ++)
            {
                $number = (int) $siret[$i];
                if (($i % 2) == 0) { if (($number *= 2) > 9) $number -= 9; }
                $sum += $number;
            }

            // le numéro est valide si la somme des chiffres est multiple de 10
            if (($sum % 10) != 0){
                $constraint->message = 'Le SIRET n\'est pas valide';
                $this->context->buildViolation($constraint->message)
                    ->addViolation();

                $error = true;
            }
        }
    }

       
    }
