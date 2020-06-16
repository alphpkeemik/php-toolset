<?php

namespace Ambientia\Toolset\Tests\Test\Fixtures;

/**
 * @author mati.andreas@ambientia.ee
 */
class CreateServiceTraitTestCustom
{
    /**
     * @var string
     */
    public $str;
    /**
     * @var int
     */
    public $i;
    /**
     * @var float
     */
    public $f;

    /**
     * @var CreateServiceTraitTestInterface
     */
    public $createServiceTraitTest;

    public function __construct(
        string $str,
        int $i,
        float $f,
        CreateServiceTraitTestInterface $createServiceTraitTest
    ) {
        $this->str = $str;
        $this->i = $i;
        $this->f = $f;
        $this->createServiceTraitTest = $createServiceTraitTest;
    }
}
