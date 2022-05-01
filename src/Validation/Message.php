<?php

namespace Xerophy\Framework\Validation;

class Message
{
    /**
     * Generate the message for the rule
     *
     * @param string $field
     * @param string $rule
     *
     * @return array|string
     * */
    public static function generate(string $field, string $rule): array|string
    {
        return str_replace('%s', $field, $rule);
    }
}