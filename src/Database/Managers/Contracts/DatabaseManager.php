<?php

namespace Xerophy\Framework\Database\Managers\Contracts;

use PDO;

interface DatabaseManager
{
    /**
     * Connect to the database
     *
     * @return PDO
     * */
    public function connect(): PDO;

    /**
     * Query the database
     *
     * @param string $query
     * @param array $values
     *
     * @return mixed
     * */
    public function query(string $query, array $values = []): mixed;

    /**
     * Create data in database
     *
     * @param $data
     *
     * */
    public function create(array $data);

    public function read(string $columns = "*", array $filter = null);

    public function update($id, $data);

    public function delete($id);

    public function getFields();
}