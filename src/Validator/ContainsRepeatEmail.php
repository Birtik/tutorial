<?php declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsRepeatEmail extends Constraint
{
    public string $message = 'Email "{{ email }}" nie jest poprawny!';
}