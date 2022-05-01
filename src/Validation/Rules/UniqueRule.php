<?php

namespace Xerophy\Framework\Validation\Rules;


use Xerophy\Framework\Validation\Rules\Contracts\RuleInterface;

class UniqueRule implements RuleInterface
{
    protected $table;

    protected $column;

    public function __construct($table, $column)
    {
        $this->table = $table;
        $this->column = $column;
    }

    public function apply(string $fieldName, string $fieldContent, array $data): bool
    {
        return !(app()->db->raw("SELECT * FROM {$this->table} WHERE {$this->column} = ?", [$fieldName]));
    }

    public function __toString(): string
    {
        return 'This %s is already taken';
    }
}