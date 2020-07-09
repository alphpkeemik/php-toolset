<?php

namespace Ambientia\Toolset\Test;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @author mati.andreas@ambientia.ee
 */
trait DoctrineSubscriberTrait
{
    private function doTestSubscriber(string $class, array $events, string $managerClass = null): void
    {
        if (!method_exists($this, 'bootKernel')) {
            throw new RuntimeException('Extend  '.KernelTestCase::class);
        }
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $doctrine = $container->get('doctrine');
        /** @var EntityManager $em */
        $em = $managerClass ? $doctrine->getManagerForClass($managerClass) : $doctrine->getManager();
        if (!$em) {
            throw new LogicException('Manager for class not found');
        }
        if (!$em instanceof EntityManagerInterface) {
            throw new LogicException('Can only be used with '.EntityManager::class);
        }

        $service = static::$container->get($class);

        $this->assertInstanceOf($class, $service);
        $this->assertInstanceOf(EventSubscriber::class, $service);

        $this->assertSame($events, $service->getSubscribedEvents());

        foreach ($events as $event) {
            $hayStack = $em
                ->getConnection()
                ->getEventManager()
                ->getListeners($event);
            $this->assertContains($service, $hayStack);
        }
    }
}
