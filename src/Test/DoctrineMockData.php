<?php

namespace Ambientia\Toolset\Test;

use BadMethodCallException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @author mati.andreas@ambientia.ee
 */
class DoctrineMockData
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;
    /**
     * @var ObjectManager[]|array
     */
    private $managers;

    /**
     * @var ObjectRepository[]|array
     */
    private $repositories;

    public function __construct(ManagerRegistry $doctrine, array $managers, array $repositories)
    {
        $this->doctrine = $doctrine;
        $this->managers = $managers;
        $this->repositories = $repositories;
    }

    /**
     * @return ManagerRegistry|MockObject
     */
    public function getDoctrine(): ManagerRegistry
    {
        return $this->doctrine;
    }

    /**
     * @param string $class
     *
     * @return ObjectRepository|MockObject
     */
    public function getRepository(string $class = null): MockObject
    {
        if (!$class) {
            if (count($this->repositories) > 1) {
                throw new BadMethodCallException('Provide class name');
            }

            return current($this->repositories);
        }

        return $this->repositories[$class];
    }

    /**
     * @param string $class
     *
     * @return ObjectManager|MockObject
     */
    public function getManager(string $class = null): MockObject
    {
        if (!$class) {
            if (count($this->managers) > 1) {
                throw new BadMethodCallException('Provide class name');
            }

            return current($this->managers);
        }

        return $this->managers[$class];
    }
}
