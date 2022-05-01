<?php

namespace Xerophy\Framework\Validation\Rules;

use Xerophy\Framework\Validation\Rules\Contracts\RuleInterface;

class MinRule implements RuleInterface
{
    protected int $min;

    public function __construct(int $min)
    {
        $this->min = $min;
    }

    public function apply(string $fieldName, string $fieldContent, array $data): bool
    {
        return (strlen($fieldContent) > $this->min);
    }

    public function __toString(): string
    {
        return "%s must be more then {$this->min}";
    }
}