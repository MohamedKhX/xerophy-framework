<?php

namespace Xerophy\Framework\Validation\Rules\Contracts;

interface RuleInterface
{
    /**
     * apply function descrption
     *
     * @param string $fieldName
     * @param string $fieldContent
     * @param array $data
     *
     * @return bool
     * */
    public function apply(string $fieldName, string $fieldContent, array $data): bool;

    /**
     * function description
     * */
    public function __toString(): string;
}