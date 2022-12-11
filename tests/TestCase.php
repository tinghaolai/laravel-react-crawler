<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use ReflectionException;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Set class property by properties array value
     *
     * Properties array - key: property name to modify, value:perperty value
     * eg: ['url' => 'test-url'] - change property url of class to be 'test-url'
     *
     * @param object|string $class
     * @param array $properties
     * @return mixed
     * @throws ReflectionException
     */
    public function setProperties(object|string $class, array $properties): mixed
    {
        if (!is_object($class)) {
            $class = new $class();
        }

        $reflection = new \ReflectionClass($class);
        foreach ($properties as $property => $value) {
            $reflectionProperty = $reflection->getProperty($property);
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($class, $value);
        }

        return $class;
    }
}
