<?php

namespace Xerophy\Framework\Validation;

use Xerophy\Framework\Container\Container;
use Xerophy\Framework\Session\Session;

class ErrorBag
{
    /*
     * Errors
     * */
    protected array $errors = [];

    /*
     * The session instance
     * */
    protected Session $session;

    /**
     * Create a new ErrorBag instance
     *
     * @param Session $session
     * @return void
     * */
    public function __construct()
    {
        $this->session = Container::$container->getObject(Session::class);
    }

    /**
     * Get errors
     *
     * @return array
     * */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Add a new Error
     *
     * @param $fieldName
     * @param $message
     *
     * @return void
     * */
    public function add($fieldName, $message): void
    {
        $this->errors[$fieldName][] = $message;
        $this->session->createError($fieldName, $message);
    }

    public function __get($key)
    {
        if(property_exists($this, $key))
            return $this->$key;
    }
}