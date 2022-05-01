<?php

namespace Xerophy\Framework\Validation;

use Xerophy\Framework\Validation\Rules\BetweenRule;
use Xerophy\Framework\Validation\Rules\RequiredRule;

trait Resolver
{
    public function resolve(string $str)
    {
        $arr = [];

        foreach (static::convertToArray($str) as $item) {

            if(is_array($item)) {
                $arr[] = [RuleMap::getRule($item[0]), ...array_slice($item, 1)];
                continue;
            }

            $arr[] = RuleMap::getRule($item);
        }

        return $arr;
    }

    public function convertToArray(string $str)
    {
        //str looks something like this 'required|email|between:3:5'
        //and this mehtod will convert it to ['required', 'email', ['between', 3, 5]]

        $arr = [];

        foreach (explode('|', $str) as $item) {
            if(str_contains($item, ':')) {
                $explodedItem = explode(':', $item);
                $arr[] = $explodedItem;
                continue;
            }
            $arr[] = $item;
        }

        return $arr;
    }
}