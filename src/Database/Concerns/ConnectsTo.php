<?php

namespace Xerophy\Framework\Database\Concerns;

use Xerophy\Framework\Database\Managers\Contracts\DatabaseManager;

trait ConnectsTo
{
    protected function connect(DatabaseManager $manager): ?\PDO
    {
        return $manager->connect();
    }
}