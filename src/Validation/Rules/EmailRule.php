<?php

namespace Xerophy\Framework\Validation\Rules;


use Xerophy\Framework\Validation\Rules\Contracts\RuleInterface;

class EmailRule implements RuleInterface
{
    public function apply($fieldName, $fieldValue, $data): bool
    {
        return (bool) preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $fieldValue);
    }

    public function __toString(): string
    {
        return 'your %s address is not a valid email address';
    }
}