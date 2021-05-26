<?php

namespace Ambientia\Toolset\Tests\Directo;

use Ambientia\Toolset\GuzzleHttp\ClientFactory;
use Ambientia\Toolset\Test\GuzzleMockTrait;
use Ambientia\Toolset\Directo\DirectoService;
use Ambientia\Toolset\Directo\ServiceException;
use Generator;
use LogicException;
use PHPUnit\Framework\TestCase;
use SimpleXMLElement;

/**
 * @author mati.andreas@ambientia.ee
 */
class DirectoServiceTest extends TestCase
{
    use GuzzleMockTrait;

    private function createGetServiceMock(string $return, string $what, array $arguments): DirectoService
    {
        $domain = uniqid();
        $key = uniqid();
        $uri = "https://login.directo.ee/xmlcore/{$domain}/xmlcore.asp";

        $options = [
            'query' => array_merge(
                [
                    'get' => 1,
                    'what' => $what,
                    'key' => $key,
                ],
                $arguments
            ),
        ];
        $clientFactory = $this->createClientFactoryMock('GET', $uri, $options, $return);

        return new DirectoService($clientFactory, $domain, $key);
    }

    public function testDirectoTsFormat(): void
    {
        $this->assertEquals('Y-m-d H:i:s', DirectoService::DIRECTO_TS_FORMAT);
    }

    /**
     * @dataProvider dataGetSuccess
     */
    public function testGetSuccess(SimpleXMLElement $expected, string $return, array $arguments = []): void
    {
        $what = uniqid();
        $service = $this->createGetServiceMock($return, $what, $arguments);
        $actual = $service->get($what, $arguments);
        $this->assertEquals($expected, $actual);
    }

    public function dataGetSuccess(): Generator
    {
        $xml = uniqid();
        $return = "<xml some_attr='$xml'/>";
        yield [
            simplexml_load_string($return),
            $return,
        ];

        $xml = uniqid();
        $return = "<xml some_attr='$xml'/>";
        $arguments = [
            uniqid() => uniqid(),
        ];
        yield [
            simplexml_load_string($return),
            $return,
            $arguments,
        ];
    }

    /**
     * @dataProvider dataGetFailure
     *
     * @param string $code
     */
    public function testGetFailure(string $message, int $code, string $return, array $arguments = []): void
    {
        $what = uniqid();
        $service = $this->createGetServiceMock($return, $what, $arguments);
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage($message);
        $this->expectExceptionCode($code);
        $service->get($what, $arguments);
    }

    public function dataGetFailure(): Generator
    {
        $return = '';
        yield [
            'Directo did not respond',
            0,
            $return,
        ];

        $code = rand();
        $desc = uniqid();
        $return = "<error desc='$desc' code='$code'/>";

        yield [
            $desc,
            $code,
            $return,
        ];
    }

    /**
     * @dataProvider dataGetInvalid
     */
    public function testGetInvalid(string $message, array $arguments): void
    {
        $clientFactoryMock = $this->createMock(ClientFactory::class);
        $domain = uniqid();
        $key = uniqid();
        $service = new DirectoService($clientFactoryMock, $domain, $key);
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage($message);
        $service->get(uniqid(), $arguments);
    }

    public function dataGetInvalid(): Generator
    {
        foreach (['get', 'what', 'key'] as $arg) {
            $arguments = [
                $arg => uniqid(),
            ];
            $message = "Can not use reserved '$arg' as argument key";

            yield [
                $message,
                $arguments,
            ];
        }
    }

    private function createPostServiceMock(string $what, string $xml, bool $xmlIsValid, string $return = null): DirectoService
    {
        $domain = uniqid();
        $key = uniqid();
        $uri = "https://login.directo.ee/xmlcore/{$domain}/xmlcore.asp";

        $options = [
            'form_params' => array_merge(
                [
                    'put' => 1,
                    'what' => $what,
                    'key' => $key,
                    'xmldata' => $xml,
                ]
            ),
        ];
        $clientFactory = $this->createClientFactoryMock('POST', $uri, $options, $return);

        return new DirectoService($clientFactory, $domain, $key);
    }

    public function testPostSuccess(): void
    {
        $xml = '<?xml version="1.0" encoding="utf-8"?><what></what>';
        $returnXml = '<?xml version="1.0" encoding="utf-8"?><results></results>';
        $what = uniqid();
        $service = $this->createPostServiceMock($what, $xml, true, $returnXml);
        $xml = $service->post($what, $xml);
        $this->assertInstanceOf(SimpleXMLElement::class, $xml);
    }

    public function testPostInvalidThrowsException(): void
    {
        $xml = '<?xml version="1.0" encoding="utf-8"?><<what></what>';
        $what = uniqid();
        $service = $this->createPostServiceMock($what, $xml, false);
        $this->expectException(ServiceException::class);
        $service->post($what, $xml);
    }
}
