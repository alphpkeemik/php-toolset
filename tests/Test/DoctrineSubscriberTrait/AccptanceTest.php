<?php

namespace Ambientia\Toolset\Tests\Test\DoctrineSubscriberTrait;

use Ambientia\Toolset\Test\DoctrineSubscriberTrait;
use Ambientia\Toolset\Tests\Test\Fixtures\DoctrineSubscriberTraitTestClass;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @author mati.andreas@ambientia.ee
 */
class DoctrineSubscriberTraitTest extends TestCase
{
    use DoctrineSubscriberTrait;

    private const MANAGER_CLASS = 'some manager class';

    private static $container;
    /**
     * @var DoctrineSubscriberTraitTestClass
     */
    private $subscriber;

    private function bootKernel()
    {
        $eventManager = $this->createMock(EventManager::class);
        $eventManager
            ->expects($this->once())
            ->method('getListeners')
            ->with(DoctrineSubscriberTraitTestClass::EVENT)
            ->willReturn([
                $this->subscriber,
            ]);

        $connection = $this->createConfiguredMock(Connection::class, [
            'getEventManager' => $eventManager,
        ]);

        $em = $this->createConfiguredMock(EntityManagerInterface::class, [
            'getConnection' => $connection,
        ]);

        $doctrine = $this->createMock(ManagerRegistry::class);
        $doctrine
            ->method('getManagerForClass')
            ->with(static::MANAGER_CLASS)
            ->willReturn($em);

        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->once())
            ->method('get')
            ->with('doctrine')
            ->willReturn($doctrine);

        $kernel = $this->createConfiguredMock(
            Kernel::class,
            [
                'getContainer' => $container,
            ]
        );

        return $kernel;
    }

    public function testAcceptance(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $class = new DoctrineSubscriberTraitTestClass();
        $container
            ->expects($this->once())
            ->method('get')
            ->with(DoctrineSubscriberTraitTestClass::class)
            ->willReturn($class);
        $this->subscriber = $class;
        static::$container = $container;
        $this->doTestSubscriber(
            DoctrineSubscriberTraitTestClass::class,
            [DoctrineSubscriberTraitTestClass::EVENT],
            static::MANAGER_CLASS
        );
    }
}
