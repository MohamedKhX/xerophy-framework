<?php

namespace Xerophy\Framework\Database;

use Xerophy\Framework\Application\Application;
use Xerophy\Framework\Support\Str;

abstract class Model
{

    protected static $instance;

    /*
     * The fallible fields
     * */
    protected array $fallible = [];

    /*
     * Create a new Model instance
     * */
    public function __construct()
    {
        foreach (self::getFields() as $field) {
            if($field !== 'id') {
                $this->fallible[] = $field;
            }
        }
    }

    public static function create(array $attributes): bool
    {
        self::$instance = static::class;

        return Application::DB()->create($attributes);
    }

    public static function all(): array
    {
        self::$instance = static::class;

        return Application::DB()->read();
    }

    public static function delete($id)
    {
        self::$instance = static::class;

        return Application::DB()->delete($id);
    }

    public static function update($id, array $attributes)
    {
        self::$instance = static::class;

        return  Application::DB()->update($id, $attributes);
    }

    public static function where($filter, $columns = '*')
    {
        self::$instance = static::class;

        return Application::DB()->read($columns, $filter);
    }

    public static function getById(int $id)
    {
        self::$instance = static::class;

        return Application::DB()->read('*', ['id', $id])[0];
    }

    public static function getModel()
    {
        return self::$instance;
    }

    public static function getTableName()
    {
        return Str::lower(Str::plural(class_basename(self::$instance)));
    }

    public static function getFields()
    {
        self::$instance = static::class;

        return Application::DB()->getField();
    }

    public function save()
    {
        $toFill = [];

        foreach ($this->fallible as $item) {
            $toFill[$item] = $this->$item;
        }

        if($this->id) {
            static::update($this->id ,$toFill);
        } else {
            static::create($toFill);
        }
    }
}