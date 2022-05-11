<?php

namespace Xerophy\Framework\Database\Managers;

use PDO;
use Xerophy\Framework\Database\Grammars\MySqlGrammar;
use Xerophy\Framework\Database\Managers\Contracts\DatabaseManager;
use Xerophy\Framework\Database\Model;

class MySqlManager implements DatabaseManager
{

    /*
     * The PDO instance.
     * */
    protected PDO $PDO;

    public function __construct()
    {
        $this->PDO = $this->createPDOInstance();
    }

    public function connect(): PDO
    {
        return $this->PDO;
    }

    protected function createPDOInstance(): PDO
    {
        return new PDO(
            $_ENV['DB_DRIVER'].
            ":host=" . $_ENV['DB_HOST'].
            ";dbname=" . $_ENV['DB_DATABASE'],
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD']
        );
    }

    /**
     * Query the database
     *
     * @param  string $query
     * @param  array $values
     * @return mixed
     */
    public function query(string $query, array $values = []): mixed
    {
        $stmt = $this->PDO->prepare($query);

        for ($i = 1; $i  < count($values); $i++) {
            $stmt->bindValue($i ,$values[$i - 1]);
        }

        var_dump($stmt->execute());
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create(array $data)
    {
        $query = MySQLGrammar::buildInsertQuery(array_keys($data));

        $stmt = $this->PDO->prepare($query);

        for ($i = 1; $i <= count($values = array_values($data)); $i++) {
            $stmt->bindValue($i, $values[$i - 1]);
        }

        return $stmt->execute();
    }

    public function read(string $columns = "*", array $filter = null)
    {
        $query = MySqlGrammar::buildSelectQuery($columns, $filter);

        $stmt = $this->PDO->prepare($query);

        $stmt->execute();


        return $stmt->fetchAll(\PDO::FETCH_CLASS, Model::getModel());

    }

    public function update($id, $data)
    {
        $query = MySqlGrammar::buildUpdateQuery(array_keys($data));

        $stmt = $this->PDO->prepare($query);

        for ($i = 1; $i <= count($values = array_values($data)); $i++) {
            $stmt->bindValue($i, $values[$i - 1]);
            if ($i == count($values)) {
                $stmt->bindValue($i + 1, $id);
            }
        }

        return $stmt->execute();
    }

    public function delete($id)
    {
        $query = MySqlGrammar::buildDeleteQuery();

        $stmt = $this->PDO->prepare($query);

        $stmt->bindValue(1, $id);

        return $stmt->execute();
    }

    public function getFields()
    {
        $stmt = $this->PDO->prepare("DESCRIBE " . Model::getTableName());
        $stmt->execute();
        return  $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}