<?php 

namespace Frame\Container;

class ServiceContainer {

    /**
     * @param string $class Fully qualified name of the class to resolve
     * @return object Instance of the resolved class
     */

        public static function make($class) {
        
        $classReflection = new \ReflectionClass($class);

        $classConstructorParams = $classReflection->getConstructor()->getParameters();

        $dependencies = static::iterateParameters($classConstructorParams,'constructor');

        return $classReflection->newInstance(...$dependencies);
    }

    /**
     * @param string $class Fully qualified name of the class to resolve
     * @param string $method Name of the method to invoke
     * @return object Instance of the resolved class
     */

    public static function invokeAction($class,$method) {

        $methodReflection = new \ReflectionMethod($class,$method);

        $methodParams = $methodReflection->getParameters();

        $dependencies = static::iterateParameters($methodParams,'method');

        $classInstance = static::make($class);

        return $methodReflection->invoke($classInstance,...$dependencies);

    }

    private static function iterateParameters($parameters = [], $target = '') {

        return \array_map(function($param) {

            $type = (string) $param->getType();

            if(class_exists($type)) {
                return static::make($type);
            } 

            else throw new \Exception("Can't resolve $target dependencies");

        },$parameters);
    }
}