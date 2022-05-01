<?php

namespace Xerophy\Framework\Validation\Rules;
use Xerophy\Framework\Validation\Rules\Contracts\RuleInterface;

class RequiredRule implements RuleInterface
{
    public function apply(string $fieldName, string $fieldContent, array $data): bool
    {
        return !empty($fieldContent);
    }

    public function __toString(): string
    {
        return "%s is required and cannot be empty";
    }
}