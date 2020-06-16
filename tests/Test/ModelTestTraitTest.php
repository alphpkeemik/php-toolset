<?php

namespace Ambientia\Toolset\Tests\Test;

use Ambientia\Toolset\Test\ModelTestTrait;
use Ambientia\Toolset\Tests\Test\Fixtures\ModelTestTraitTestModel;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * @author mati.andreas@ambientia.ee
 */
class ModelTestTraitTest extends TestCase
{
    use ModelTestTrait;

    public function testCreateEntity(): void
    {
        /** @var ModelTestTraitTestModel $model */
        $custom1 = uniqid();
        $custom2 = [uniqid()];
        $model = $this->createEntity(ModelTestTraitTestModel::class, [
            'setCustom1' => $custom1,
            'setCustom2' => [$custom2]
        ]);

        $this->assertNotEmpty($model->str);
        $this->assertTrue(is_string($model->str));

        $this->assertNotEmpty($model->i);
        $this->assertTrue(is_int($model->i));

        $this->assertNotEmpty($model->f);
        $this->assertTrue(is_float($model->f));

        $this->assertTrue(is_bool($model->b));

        $this->assertNotEmpty($model->arr);
        $this->assertTrue(is_array($model->arr));

        $this->assertNotEmpty($model->dateTime);
        $this->assertTrue($model->dateTime instanceof DateTime);

        $this->assertNull($model->unknownType);
        $this->assertNull($model->allowsNull);

        $this->assertSame($custom1, $model->custom1);
        $this->assertSame($custom2, $model->custom2);
    }

}
