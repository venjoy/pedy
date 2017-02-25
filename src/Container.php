<?php

namespace Venjoy\Pedy;

use ReflectionClass;
use Exception;

class Container
{
    /**
     * Associative array for storing initiated objects.
     * 
     * keys represent className and
     * values represent initiated object
     * 
     * @var Array
     */
    protected $bag;

    /**
     * get object by className
     * 
     * build the object by recursively calling its contructor
     * params
     * 
     * @param string $className
     * @return Object
     */
    public function get(String $className)
    {
        if ( ! class_exists($className) ) 
        {
            throw new Exception("$className was not found by DIContainer");
            return;
        }

        if ( isset($this->bag[$className]) )
        {
            return $this->bag[$className];
        }

        // use reflection to get dependencies
        $reflector = new ReflectionClass($className);

        $constructor = $reflector->getConstructor();

        if ($constructor) 
        {
            return $this->buildWithConstructor($className, $constructor);
        } 
        else 
        {
            return $this->buildWithoutConstructor($className);
        }
    }

    /**
     * build object by className with constructor
     * 
     * build the object by recursively calling its contructor
     * params
     * 
     * @param string $className
     * @param mixed $constructor
     * @return Object
     */
    protected function buildWithConstructor(String $className, $constructor)
    {
        $params = $constructor->getParameters();
        $dependencies = [];
        
        foreach ($params as $param) 
        {
            $dependencies[] = $this->get($param->getClass()->name);
        }

        $this->bag[$className] = new $className(...$dependencies);

        return $this->bag[$className];        
    }

    /**
     * build object by className without constructor
     * 
     * simply build the object by new operator
     * 
     * @param string $className
     * @param mixed $constructor
     * @return Object
     */
    protected function buildWithoutConstructor(String $className)
    {
        $this->bag[$className] = new $className();

        return $this->bag[$className];        
    }
}
