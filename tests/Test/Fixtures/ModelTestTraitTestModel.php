<?php

namespace Ambientia\Toolset\Tests\Test\Fixtures;

use DateTime;

/**
 * @author mati.andreas@ambientia.ee
 */
class ModelTestTraitTestModel
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
     * @var iterable
     */
    public $iterable;

    public $unknownType;

    public $allowsNull;

    public $custom1;
    public $custom2;

    public function setStr(string $str): void
    {
        $this->str = $str;
    }

    public function setI(int $i): void
    {
        $this->i = $i;
    }

    public function setF(float $f): void
    {
        $this->f = $f;
    }

    public function setB(bool $b): void
    {
        $this->b = $b;
    }

    public function setArr(array $arr): void
    {
        $this->arr = $arr;
    }

    public function setDateTime(DateTime $dateTime): void
    {
        $this->dateTime = $dateTime;
    }

    public function setIterable(iterable $iterable): void
    {
        $this->iterable = $iterable;
    }

    public function setUnknownType($unknownType): void
    {
        $this->unknownType = $unknownType;
    }

    public function setAllowsNull($allowsNull = null): void
    {
        $this->allowsNull = $allowsNull;
    }

    public function setCustom1(string $custom1): void
    {
        $this->custom1 = $custom1;
    }

    public function setCustom2(array $custom2): void
    {
        $this->custom2 = $custom2;
    }
}
