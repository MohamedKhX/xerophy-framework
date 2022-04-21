<?php

namespace Xerophy\Framework\Routing;

class RouteAction
{
    /**
     * Parse action
     * */
    public static function parse(callable|array $action)
    {
        if(is_callable($action)) return $action;

        if(self::is_valid_array($action))
        {

        }
    }


}