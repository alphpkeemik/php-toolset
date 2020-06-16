<?php

namespace Ambientia\Toolset\Tests\Test;

use Ambientia\Toolset\Test\CreateServiceTrait;
use Ambientia\Toolset\Tests\Test\Fixtures\CreateServiceTraitTestCustom;
use Ambientia\Toolset\Tests\Test\Fixtures\CreateServiceTraitTestInject;
use Ambientia\Toolset\Tests\Test\Fixtures\CreateServiceTraitTestInterface;
use Ambientia\Toolset\Tests\Test\Fixtures\CreateServiceTraitTestMain;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * @author mati.andreas@ambientia.ee
 */
class CreateServiceTraitTest extends TestCase
{
    use CreateServiceTrait;

    public function testCreateService(): void
    {
        /** @var CreateServiceTraitTestMain $service */
        $custom = (object) [uniqid() => uniqid()];
        $customClass = $this->createMock(CreateServiceTraitTestCustom::class);
        $service = $this->createService(
            CreateServiceTraitTestMain::class,
            [
                'custom' => $custom,
            ],
            $customClass
        );

        $this->assertNotEmpty($service->str);
        $this->assertTrue(is_string($service->str));

        $this->assertNotEmpty($service->i);
        $this->assertTrue(is_int($service->i));

        $this->assertNotEmpty($service->f);
        $this->assertTrue(is_float($service->f));
        $this->assertTrue(is_bool($service->b));

        $this->assertNotEmpty($service->arr);
        $this->assertTrue(is_array($service->arr));

        $this->assertNotEmpty($service->dateTime);
        $this->assertInstanceOf(DateTime::class, $service->dateTime);

        $this->assertInstanceOf(CreateServiceTraitTestInterface::class, $service->createServiceTraitTestInterface);
        $this->assertInstanceOf(CreateServiceTraitTestInject::class, $service->createServiceTraitTestInject);

        $this->assertSame($customClass, $service->createServiceTraitTestCustom);
        $this->assertSame($custom, $service->custom);
    }
}
