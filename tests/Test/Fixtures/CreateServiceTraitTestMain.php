<?php

namespace Ambientia\Toolset\Tests\Test\Fixtures;

use DateTime;

/**
 * @author mati.andreas@ambientia.ee
 */
class CreateServiceTraitTestMain
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
    public $custom;
    /**
     * @var bool
     */
    public $b;
    /**
     * @var array
     */
    public $arr;

    /**
     * @var DateTime
     */
    public $dateTime;

    /**
     * @var CreateServiceTraitTestCustom
     */
    public $createServiceTraitTestCustom;
    /**
     * @var CreateServiceTraitTestInterface
     */
    public $createServiceTraitTestInterface;

    /**
     * @var CreateServiceTraitTestInject
     */
    public $createServiceTraitTestInject;

    public function __construct(string $str, int $i, float $f, $custom, bool $b, array $arr, DateTime $dateTime, CreateServiceTraitTestCustom $createServiceTraitTestCustom, CreateServiceTraitTestInterface $createServiceTraitTestInterface, CreateServiceTraitTestInject $createServiceTraitTestInject)
    {
        $this->str = $str;
        $this->i = $i;
        $this->f = $f;
        $this->custom = $custom;
        $this->b = $b;
        $this->arr = $arr;
        $this->dateTime = $dateTime;
        $this->createServiceTraitTestCustom = $createServiceTraitTestCustom;
        $this->createServiceTraitTestInterface = $createServiceTraitTestInterface;
        $this->createServiceTraitTestInject = $createServiceTraitTestInject;
    }
}
