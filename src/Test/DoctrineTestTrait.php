<?php

namespace Ambientia\Toolset\Test;

use Doctrine\DBAL\Logging\EchoSQLLogger;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Trait for helping doctrine testing.
 *
 * @author mati.andreas@ambientia.ee
 *
 * @see AbstractDoctrineTestCase for creating em
 */
trait DoctrineTestTrait
{
    private function getDoctrine(): ManagerRegistry
    {
        if (!(
            $this instanceof KernelTestCase
            or
            property_exists($this, 'kernel')
        )) {
            throw new RuntimeException('Extend ' . KernelTestCase::class);
        }
        if (!static::$kernel instanceof KernelInterface) {
            throw new RuntimeException('Boot kernel first');
        }

        return self::$kernel->getContainer()->get('doctrine');
    }

    /**
     * @internal
     */
    private function getManager(): ObjectManager
    {
        return $this->getDoctrine()->getManager();
    }

    private function persistEntity($item, ObjectManager $em = null)
    {
        if (!$em) {
            $this->getManager();
        }
        $em->persist($item);
        $em->flush();
        $em->clear();

        return $em->find(get_class($item), $item->getId());
    }

    private function createAndPersistEntity(string $class, array $custom = [], ObjectManager $em = null)
    {
        if (!method_exists($this, 'createEntity')) {
            throw new RuntimeException('Include also ' . ModelTestTrait::class);
        }
        $item = $this->createEntity($class, $custom);

        return $this->persistEntity($item, $em);
    }

    /**
     * debugging function
     *
     * @param EntityManager|null $em
     */
    private function enableLogger(EntityManager $em = null): void
    {
        if (!$em) {
            $em = $this->getManager();
            if (!$em instanceof KernelTestCase) {
                throw new RuntimeException('Extend ' . KernelTestCase::class);
            }
        }
        $em->getConnection()->getConfiguration()->setSQLLogger(new EchoSQLLogger());
    }
}
