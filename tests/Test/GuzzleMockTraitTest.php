<?php

namespace Ambientia\Toolset\Tests\Test;

use Ambientia\Toolset\GuzzleHttp\ClientFactory;
use Ambientia\Toolset\Test\GuzzleMockTrait;
use PHPUnit\Framework\TestCase;

/**
 * @author mati.andreas@ambientia.ee
 */
class GuzzleMockTraitTest extends TestCase
{
    use GuzzleMockTrait;

    public function testClientNoResponse(): void
    {
        $method = uniqid();
        $uri = uniqid();
        $options = [uniqid()];
        $clientFactory = $this->createClientFactoryMock($method, $uri, $options);

        $this->assertInstanceOf(ClientFactory::class, $clientFactory);
        $clientFactory->getClient()->request($method, $uri, $options);
    }

    public function testClientWithResponse(): void
    {
        $method = uniqid();
        $uri = uniqid();
        $options = [uniqid()];
        $response = uniqid();
        $clientFactory = $this->createClientFactoryMock($method, $uri, $options, $response);

        $this->assertInstanceOf(ClientFactory::class, $clientFactory);
        (string) $clientFactory->getClient()->request($method, $uri, $options)->getBody();
    }
}
