<?php

namespace Ambientia\Toolset\Tests\Test\DoctrineSubscriberTrait;

use Ambientia\Toolset\Test\DoctrineSubscriberTrait;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @author mati.andreas@ambientia.ee
 */
class ExtendKernelTest extends TestCase
{
    use DoctrineSubscriberTrait;

    public function testRequiresKernelTestCase(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Extend  '.KernelTestCase::class);
        $this->doTestSubscriber(uniqid(), []);
    }
}
