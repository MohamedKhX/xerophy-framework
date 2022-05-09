<?php

namespace Xerophy\Framework\Database;

use Xerophy\Framework\Database\Concerns\ConnectsTo;
use Xerophy\Framework\Database\Managers\Contracts\DatabaseManager;

class Database
{
    use ConnectsTo;

    /*
     * Database manager instance.
     * */
    protected DatabaseManager $manager;

    /**
     * Create a new Database instance
     *
     * @param DatabaseManager $manager
     * @return void
     * */
    public function __construct(DatabaseManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Init the database
     *
     * @return void
     * */
    public function init(): void
    {
        $this->connect($this->manager);
    }

    /**
     * Get a raw from the database
     *
     * @param string $query
     * @param array $value
     *
     * @return array
     * */
    public function raw(string $query, $value = []): array
    {
        return $this->manager->query($query, $value);
    }

    /**
     * Create data and store it in the database and return it
     *
     * @param array $data
     * @return bool
     * */
    public function create(array $data): bool
    {
        return $this->manager->create($data);
    }

    /**
     * Update and return specific element from the database
     *
     * @param string|int $id
     * @param array $data
     *
     * @return mixed
     * */
    public function update(string|int $id, array $data)
    {
        return $this->manager->update($id, $data);
    }

    /**
     * return specific data from the database
     *
     * @param string $columns
     * @param null $filter
     *
     * @return array
     * */
    public function read(string $columns = "*", $filter = null): array
    {
        return $this->manager->read($columns, $filter);
    }

    /**
     * Delete a row from the database
     *
     * @param int $id
     * @return array
     */
    public function delete(int $id)
    {
        return $this->manager->delete($id);
    }

    public function getField()
    {
        return $this->manager->getFields();
    }
}