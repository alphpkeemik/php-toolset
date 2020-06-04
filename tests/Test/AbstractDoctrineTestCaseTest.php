<?php

namespace Ambientia\Toolset\Tests\Test;

use Ambientia\Toolset\Test\AbstractDoctrineTestCase;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @author mati.andreas@ambientia.ee
 */
class AbstractDoctrineTestCaseTest extends TestCase
{
    public function testCreateManager(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $kernel = $this->createConfiguredMock(
            Kernel::class,
            [
                'getContainer' => $container,
            ]
        );

        $doctrine = $this->createMock(ManagerRegistry::class);

        $container
            ->method('get')
            ->willReturnMap([
                ['doctrine', $doctrine],
            ]);
        $platform = $this->createConfiguredMock(AbstractPlatform::class, [
            'getName' => 'sqlite',
        ]);
        $driver = $this->createConfiguredMock(Driver::class, [
            'getDatabasePlatform' => $platform,
        ]);

        $connection = $this->createConfiguredMock(Connection::class, [
            'getDriver' => $driver,
        ]);
        $file = uniqid().'/var/test-data.db';
        $driver
            ->method('getDatabase')
            ->willReturnMap([
                [$connection, $file],
            ]);

        $classMetaData = [uniqid()];
        $metadataFactory = $this->createConfiguredMock(ClassMetadataFactory::class, [
            'getAllMetadata' => $classMetaData,
        ]);
        $em = $this->createConfiguredMock(EntityManager::class, [
            'getConnection' => $connection,
            'getMetadataFactory' => $metadataFactory,
        ]);
        $class = uniqid();

        $doctrine
            ->method('getManagerForClass')
            ->willReturnMap([
                [$class, $em],
            ]);
        $filesystem = $this->createMock(Filesystem::class);
        $filesystem
            ->expects($this->once())
            ->method('remove')
            ->with($file);

        $schemaTool = $this->createMock(SchemaTool::class);
        $schemaTool
            ->expects($this->once())
            ->method('updateSchema')
            ->with($classMetaData, false);

        $script = new class() extends AbstractDoctrineTestCase {
            public static $kernel;
            public static $filesystem;
            public static $schemaTool;

            protected static function createKernel(array $options = [])
            {
                return static::$kernel;
            }

            public function createManager(string $class): EntityManager
            {
                return parent::createManager($class);
            }

            protected function createFilesystem(): Filesystem
            {
                return static::$filesystem;
            }

            protected function createSchemaManager(EntityManager $entityManager): SchemaTool
            {
                return static::$schemaTool;
            }
        };
        $script::$kernel = $kernel;
        $script::$filesystem = $filesystem;
        $script::$schemaTool = $schemaTool;

        $actual = $script->createManager($class);

        $this->assertEquals($em, $actual);
    }
}
