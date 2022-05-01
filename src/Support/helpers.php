<?php

use Xerophy\Framework\Routing\Redirector;

function redirect(?string $to = null): Redirector
{
    static $redirector;

    if(!$redirector) {
        $redirector = new Redirector();
    }

    if($to) {
        $redirector->to($to);
    }

    return $redirector;
}


function errors(): array|null
{
    echo 'test';
    return $_SESSION['errors'];
}