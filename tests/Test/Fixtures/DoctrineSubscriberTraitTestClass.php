<?php

namespace Ambientia\Toolset\Tests\Test\Fixtures;

use Doctrine\Common\EventSubscriber;

/**
 * @author mati.andreas@ambientia.ee
 */
class DoctrineSubscriberTraitTestClass implements EventSubscriber
{
    public const EVENT = 'some event';

    public function getSubscribedEvents()
    {
        return [
            static::EVENT,
        ];
    }
}
