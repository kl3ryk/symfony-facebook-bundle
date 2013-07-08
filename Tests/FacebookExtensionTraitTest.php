<?php

namespace Laelaps\Bundle\Facebook\Tests;

use Laelaps\Bundle\Facebook\FacebookExtensionTrait;
use Laelaps\Bundle\Facebook\Tests\Fixture\Extension\BasicFacebookExtensionWithTrait;
use PHPUnit_Framework_TestCase;
use ReflectionClass;

class FacebookExtensionTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @param string $class
     * @return array
     */
    private function getClassMethodsMetadata($class)
    {
        $classReflection = new ReflectionClass($class);
        $classMethods = $classReflection->getMethods();

        foreach ($classMethods as $index => $method) {
            if ($method->getDeclaringClass()->getName() !== $class) {
                unset($classMethods[$index]);
            }
        }

        $classMethodNames = [];
        foreach ($classMethods as $method) {
            $classMethodNames[] = $method->getName();
        }

        return [$classMethods, $classMethodNames];
    }

    public function testThatTraitMatchesInterface()
    {
        list($interfaceMethods, $interfaceMethodNames) = $this->getClassMethodsMetadata('Laelaps\Bundle\Facebook\FacebookExtensionInterface');
        list($traitMethods, $traitMethodNames) = $this->getClassMethodsMetadata('Laelaps\Bundle\Facebook\FacebookExtensionTrait');

        $this->assertEquals($interfaceMethodNames, $traitMethodNames, 'interface and trait have different methods');
    }

    public function testThatExtensionUsingTraitImplementigInterfaceCanBeInstanciated()
    {
        new BasicFacebookExtensionWithTrait;
    }
}
