<?php

namespace Xerophy\Framework\Validation\Rules;


use Xerophy\Framework\Validation\Rules\Contracts\RuleInterface;

class AlnumRule implements RuleInterface
{
    public function apply(string $fieldName, string $fieldContent, array $data): bool
    {
        return (bool) preg_match('/^[a-zA-Z0-9]+/', $value);
    }

    public function __toString(): string
    {
        return "%s must be characters and numbers only";
    }
}