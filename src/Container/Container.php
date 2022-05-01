<?php

namespace Xerophy\Framework\Container;

use Xerophy\Framework\Container\Exceptions\ContainerException;
use Xerophy\Framework\Http\Request;
use Xerophy\Framework\Routing\UrlGenerator;

class Container
{
    /*
     * SharedObjects between classes
     * */
    public array $sharedObjects = [];

    /*
     * Not sharedObjects
     * */
    protected array $objects = [];

    /*
     * The global container instance (created and the application)
     * Note (This container is linked to the application)
     * */
    public static Container $container;

    /**
     * Get an object from $this->sharedObjects
     *
     * @param string $id
     * @return object
     * */
    public function getObject(string $id): object
    {
        if($this->has($id)) {
            return $this->getSharedObject($id);
        }

        return $this->set($id);
    }

    /**
     * Create and return the new created object
     *
     * @param string $id
     * @param string $name
     *
     * @return object
     * */
    public function getNewObject(string $id, string $name): object
    {

    }

    /**
     * Get object by name from $this->objects
     *
     * @param string $name
     * @return object
     * */
    public function getNamedObject(string $name): object
    {

    }

    protected function getSharedObject(string $id): object
    {
        return $this->sharedObjects[$id];
    }

    /**
     * Check if $this->objects has the same name
     *
     * @param string $name
     * @return bool
     * */
    public function hasNamedObject(string $name): bool
    {

    }

    /**
     * Check if $this->sharedObjects has the same object
     *
     * @param string $id
     * @return bool
     * */
    public function has(string $id): bool
    {
        return (bool) isset($this->sharedObjects[$id]);
    }

    /**
     * Set already created object to $this->sharedObjects
     *
     * @param object $object
     * @return void
     * */
    public function setObject(object $object): void
    {

    }

    /**
     * Create a new object and set it in $this->sharedObjects
     *
     * @param string $id
     * @return void
     * */
    public function set(string $id): object
    {
        return $this->sharedObjects[$id] = $this->createObject($id);
    }

    /**
     * Create a new object by full class name and set thier dependencies (if any)
     *
     * @param string $id
     * @return object
     * */
    public function createObject(string $id): object
    {
        $reflectionClass = new \ReflectionClass($id);

        $constructer = $reflectionClass->getConstructor();

        if(! $constructer) {
           return new $id;
        }

        $params = $constructer->getParameters();

        if(! $params) {
            return new $id;
        }

        $dependencies = array_map(function (\ReflectionParameter $parameter) {
            $name = $parameter->getName();
            $type = $parameter->getType();


            if(! $type) {
                throw new ContainerException(
                    'Failed to resolve class "' . $id . '" because param "' . $name . '" is missing a type hint'
                );
            }

            if($type instanceof \ReflectionUnionType) {
                throw new ContainerException(
                    'Failed to resolve class "' . $id . '" because of union type for param "' . $name . '"'
                );
            }

            if ($type instanceof \ReflectionNamedType && ! $type->isBuiltin()) {
                return $this->getObject($type->getName());
            }

            throw new ContainerException(
                'Failed to resolve class "' . $id . '" because invalid param "' . $name . '"'
            );
        }
        ,$params);

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}