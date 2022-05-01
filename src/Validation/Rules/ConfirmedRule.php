<?php

namespace Xerophy\Framework\Validation\Rules;


use Xerophy\Framework\Validation\Rules\Contracts\RuleInterface;

class ConfirmedRule implements RuleInterface
{
    public function apply(string $fieldName, string $fieldContent, array $data): bool
    {
        return ($data[$fieldName] === $data[$fieldName . '_confirmation']);
    }

    public function __toString(): string
    {
        return '%s does not match %s confirmation';
    }
}