<?php

namespace Ambientia\Toolset\Test;

use DateTime;
use LogicException;
use ReflectionClass;

/**
 * @author mati.andreas@ambientia.ee
 */
trait CreateServiceTrait
{
    private function createService(string $mainClass, ...$arguments)
    {
        $rc = new ReflectionClass($mainClass);
        $namedArguments = [];
        foreach ($arguments as $i => $arg) {
            if (is_array($arg)) {
                $namedArguments = $arg;
                unset($arguments[$i]);
                break;
            }
        }
        $args = [];
        foreach ($rc->getConstructor()->getParameters() as $parameter) {
            $name = $parameter->getName();
            if (key_exists($name, $namedArguments)) {
                $args[] = $namedArguments[$name];
                continue;
            }
            $typeName = $parameter->getType()->getName();
            switch ($typeName) {
                case 'string':
                    $args[] = uniqid();
                    continue 2;
                case 'int':
                case 'float':
                    $args[] = rand();
                    continue 2;
                case 'bool':
                    $args[] = rand(true, false);
                    continue 2;
                case 'array':
                case 'iterable':
                    $args[] = [rand(), uniqid()];
                    continue 2;
                case 'DateTime':
                    $args[] = new DateTime(date('Y-m-d H:i:s', rand(1, time())));
                    continue 2;
            }

            if (!(class_exists($typeName) or interface_exists($typeName))) {
                throw new LogicException("Provide named argument for non class $name:$typeName");
            }
            foreach ($arguments as $i => $arg) {
                if (is_object($arg) and is_a($arg, $typeName)) {
                    $args[] = $arg;
                    unset($arguments[$i]);
                    continue 2;
                }
            }
            $args[] = $this->createMock($typeName);
        }
        foreach ($arguments as $argument) {
            $description = is_object($argument) ? get_class($argument) : gettype($argument);
            throw new LogicException("Excess argument '$description'");
        }

        return new $mainClass(...$args);
    }
}
