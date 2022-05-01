<?php

namespace Xerophy\Framework\Validation\Rules;


use Xerophy\Framework\Validation\Rules\Contracts\RuleInterface;

class BetweenRule implements RuleInterface
{
    protected int $max;
    protected int $min;

    public function __construct(int $min, int $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function apply(string $fieldName, string $fieldContent, array $data): bool
    {
        if(strlen($fieldContent) < $this->min)
            return false;
        if(strlen($fieldContent) > $this->max)
            return false;
        else
            return true;
    }

    public function __toString(): string
    {
        return "%s must be between {$this->min} and {$this->max}";
    }
}