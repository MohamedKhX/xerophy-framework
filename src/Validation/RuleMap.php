<?php

namespace Xerophy\Framework\Validation;

use Xerophy\Framework\Validation\Rules\AlnumRule;
use Xerophy\Framework\Validation\Rules\BetweenRule;
use Xerophy\Framework\Validation\Rules\ConfirmedRule;
use Xerophy\Framework\Validation\Rules\Contracts\RuleInterface;
use Xerophy\Framework\Validation\Rules\EmailRule;
use Xerophy\Framework\Validation\Rules\MaxRule;
use Xerophy\Framework\Validation\Rules\MinRule;
use Xerophy\Framework\Validation\Rules\RequiredRule;
use Xerophy\Framework\Validation\Rules\UniqueRule;

class RuleMap
{
    protected static array $map = [
        'required'      => RequiredRule::class,
        'alnum'         => AlnumRule::class,
        'max'           => MaxRule::class,
        'min'           => MinRule::class,
        'between'       => BetweenRule::class,
        'email'         => EmailRule::class,
        'confirmed'     => ConfirmedRule::class,
        'unique'        => UniqueRule::class
    ];

    public static function getRule(string $rule)
    {
        return static::$map[$rule];
    }
}