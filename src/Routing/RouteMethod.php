<?php

namespace Xerophy\Framework\Routing;

enum RouteMethod: string
{
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case DELETE = 'DELETE';
}