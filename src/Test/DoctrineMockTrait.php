<?php

namespace Ambientia\Toolset\Test;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;

/**
 * @author mati.andreas@ambientia.ee
 */
trait DoctrineMockTrait
{
    private function createDoctrineMock(string ...$class): DoctrineMockData
    {
        $managers = [];
        $repos = [];
        $map = [];
        foreach ($class as $row) {
            $em = $this->createMock(ObjectManager::class);
            $repo = $this->createMock(ObjectRepository::class);
            $em
                ->method('getRepository')
                ->with($row)
                ->willReturn($repo);
            $managers[$row] = $em;
            $repos[$row] = $repo;
            $map[] = [
                $row,
                $em,
            ];
        }
        $doctrine = $this->createMock(ManagerRegistry::class);
        $doctrine
            ->method('getManagerForClass')
            ->will($this->returnValueMap($map));

        return new DoctrineMockData($doctrine, $managers, $repos);
    }
}
