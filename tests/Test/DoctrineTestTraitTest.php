<?php

namespace Ambientia\Toolset\Tests\Test;

use Ambientia\Toolset\Test\DoctrineTestTrait;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author mati.andreas@ambientia.ee
 */
class DoctrineTestTraitTest extends TestCase
{
    use DoctrineTestTrait;

    private static $kernel;

    public function testGetDoctrine(): void
    {
        $expected = $this->createMock(ManagerRegistry::class);
        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->willReturnCallback(function (string $service) use ($expected) {
                if ('doctrine' === $service) {
                    return $expected;
                }
            });
        $kernel = $this->createConfiguredMock(KernelInterface::class, [
            'getContainer' => $container,
        ]);
        static::$kernel = $kernel;
        $actual = $this->getDoctrine();
        $this->assertSame($expected, $actual);
    }

    public function testPersistEntity(): void
    {
        $findObject = uniqid();
        $em = $this->createConfiguredMock(ObjectManager::class, [
            'find' => $findObject,
        ]);

        $actual = $this->persistEntity(
            new class() {
                public function getId()
                {
                }
            },
            $em
        );

        $this->assertEquals($findObject, $actual);
    }

    private function createEntity(string $class, array $custom)
    {
        return new class() {
            public function getId()
            {
            }
        };
    }

    public function testCreateAndPersistEntity(): void
    {
        $findObject = uniqid();
        $em = $this->createConfiguredMock(ObjectManager::class, [
            'find' => $findObject,
        ]);

        $actual = $this->createAndPersistEntity(
            uniqid(),
            [],
            $em
        );

        $this->assertEquals($findObject, $actual);
    }
}
