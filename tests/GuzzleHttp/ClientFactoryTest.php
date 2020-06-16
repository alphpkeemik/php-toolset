<?php

namespace Ambientia\Toolset\Tests\GuzzleHttp;

use Ambientia\Toolset\GuzzleHttp\ClientFactory;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @author mati.andreas@ambientia.ee
 */
class ClientFactoryTest extends TestCase
{
    public function testCreateClient(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $script = new ClientFactory($logger);

        $actual = $script->getClient();

        $this->assertInstanceOf(Client::class, $actual);
        /** @var HandlerStack $handler */
        $handler = $actual->getConfig('handler');
        $this->assertInstanceOf(HandlerStack::class, $handler);
    }
}
