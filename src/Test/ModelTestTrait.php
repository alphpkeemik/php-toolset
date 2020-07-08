<?php

namespace Ambientia\Toolset\Test;

use DateTime;
use RuntimeException;
use ReflectionClass;
use ReflectionMethod;

/**
 * @author mati.andreas@ambientia.ee
 */
trait ModelTestTrait
{
    private function createEntity(string $class, array $custom = [])
    {

        $rc = new ReflectionClass($class);
        if ($rc->getConstructor()) {
            if (!method_exists($this, 'createService')) {
                throw new RuntimeException('Include also ' . CreateServiceTrait::class);
            }
            $item = $this->createService($class);
        } else {
            $item = new $class();
        }

        $debug = [];
        foreach ($rc->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $methodName = $method->getName();
            if (key_exists($methodName, $custom)) {
                continue;
            }
            if (!preg_match('/^set(.+)/', $methodName)) {
                continue;
            }
            $args = [];
            foreach ($method->getParameters() as $parameter) {
                $type = $parameter->getType();

                if (!$type) {
                    $debug[] = "Parameter type missing for '$methodName'";
                    continue 2;
                }
                $typeName = $type->getName();

                switch ($typeName) {
                    case 'string':
                        $args[] = uniqid();
                        break;
                    case 'int':
                    case 'float':
                        $args[] = rand();
                        break;
                    case 'bool':
                        $args[] = rand(true, false);
                        break;
                    case 'array':
                        $args[] = [rand(), uniqid()];
                        break;
                    case 'DateTime':
                        $args[] = new DateTime(date('Y-m-d H:i:s', rand(1, time())));
                        break;
                    default:
                        if ($parameter->allowsNull()) {
                            continue 3;
                        }
                        $debug[] = "Parameter type '$typeName' for '$methodName' not supported";
                        continue 3;
                }
            }
            $item->{$methodName}(...$args);
        }

        foreach ($custom as $method => $args) {
            if (!is_array($args)) {
                $args = [$args];
            }
            $item->{$method}(...$args);
        }

        // comment this line out to know why some setters are not used
        // dump($debug);

        return $item;
    }
}
