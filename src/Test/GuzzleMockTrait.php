<?php

namespace Ambientia\Toolset\Test;

use Ambientia\Toolset\GuzzleHttp\ClientFactory;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @author mati.andreas@ambientia.ee
 */
trait GuzzleMockTrait
{
    private function createClientFactoryMock(string $method, string $uri, array $options = [], string $return = null): ClientFactory
    {
        $clientFactory = $this->createMock(ClientFactory::class);
        $client = $this->createMock(Client::class);
        $message = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $clientFactory
            ->expects($this->once())
            ->method('getClient')
            ->willReturn($client);

        $client
            ->expects($this->once())
            ->method('request')
            ->with($method, $uri, $options)
            ->willReturn($message);

        if ($return) {
            $message
                ->expects($this->once())
                ->method('getBody')
                ->willReturn($stream);

            $stream
                ->expects($this->once())
                ->method('__toString')
                ->willReturn($return);
        }

        return $clientFactory;
    }
}
