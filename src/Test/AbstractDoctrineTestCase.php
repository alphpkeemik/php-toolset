<?php

namespace Ambientia\Toolset\Test;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class for testing doctrine model and definitions.
 *
 * @author mati.andreas@ambientia.ee
 */
abstract class AbstractDoctrineTestCase extends WebTestCase
{
    /**
     * @param string $class doctrine model class name
     *
     * @todo add manager option
     */
    protected function createManager(string $class): EntityManager
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $doctrine = $container->get('doctrine');
        /** @var EntityManager $em */
        $em = $doctrine->getManagerForClass($class);
        if (!$em instanceof EntityManager) {
            throw new LogicException('Can only be used with '.EntityManager::class);
        }

        $connection = $em->getConnection();
        $driver = $connection->getDriver();

        $platform = $connection->getDriver()->getDatabasePlatform();

        if ('sqlite' !== $platform->getName()) {
            throw new LogicException('Can only be used with sqlite');
        }

        $db = $driver->getDatabase($connection);
        if (!preg_match('|/var/test-data.db$|', $db)) {
            throw new LogicException('SQL data is expected to be in var/test-data.db');
        }
        $fs = $this->createFilesystem();
        $fs->remove($db);

        $s = $this->createSchemaManager($em);
        $s->updateSchema($em->getMetadataFactory()->getAllMetadata(), false);

        return $em;
    }

    protected function createFilesystem(): Filesystem
    {
        return new Filesystem();
    }

    protected function createSchemaManager(EntityManager $em): SchemaTool
    {
        return new SchemaTool($em);
    }
}
