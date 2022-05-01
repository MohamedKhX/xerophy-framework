<?php

namespace Xerophy\Framework\Validation\Rules;


use Xerophy\Framework\Validation\Rules\Contracts\RuleInterface;

class MaxRule implements RuleInterface
{
    protected int $max;

    public function __construct(int $max)
    {
        $this->max = $max;
    }

    public function apply(string $fieldName, string $fieldContent, array $data): bool
    {
        return (strlen($fieldContent) < $this->max);
    }

    public function __toString(): string
    {
        return "%s must be less then {$this->max}";
    }
}