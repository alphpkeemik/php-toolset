<?php

namespace Ambientia\Toolset\Tests\Test;

use Ambientia\Toolset\Test\DoctrineMockData;
use Ambientia\Toolset\Test\DoctrineMockTrait;
use PHPUnit\Framework\TestCase;

/**
 * @author mati.andreas@ambientia.ee
 */
class DoctrineMockTraitTest extends TestCase
{
    use DoctrineMockTrait;

    public function testCreateDoctrineMockSingle(): void
    {
        $class1 = uniqid();
        $dm = $this->createDoctrineMock($class1);

        $this->assertInstanceOf(DoctrineMockData::class, $dm);
        $doctrine = $dm->getDoctrine();

        $em1 = $doctrine->getManagerForClass($class1);
        $this->assertSame(
            $em1,
            $dm->getManager()
        );
        $this->assertSame(
            $em1->getRepository($class1),
            $dm->getRepository()
        );
    }

    public function testCreateDoctrineMockMultiple(): void
    {
        $class1 = uniqid();
        $class2 = uniqid();
        $dm = $this->createDoctrineMock($class1, $class2);

        $this->assertInstanceOf(DoctrineMockData::class, $dm);
        $doctrine = $dm->getDoctrine();

        $em1 = $doctrine->getManagerForClass($class1);
        $this->assertSame(
            $em1,
            $dm->getManager($class1)
        );
        $this->assertSame(
            $em1->getRepository($class1),
            $dm->getRepository($class1)
        );

        $em2 = $doctrine->getManagerForClass($class2);
        $this->assertSame(
            $em2,
            $dm->getManager($class2)
        );
        $this->assertSame(
            $em2->getRepository($class2),
            $dm->getRepository($class2)
        );
    }
}
