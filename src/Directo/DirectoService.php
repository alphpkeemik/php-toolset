<?php

namespace Ambientia\Toolset\Directo;

use Ambientia\Toolset\GuzzleHttp\ClientFactory;
use LogicException;
use Psr\Http\Message\MessageInterface;
use SimpleXMLElement;

/**
 * @author mati.andreas@ambientia.ee
 */
class DirectoService
{
    const DIRECTO_TS_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var ClientFactory
     */
    private $clientFactory;

    /**
     * @var string
     */
    private $domain;

    /**
     * @var string
     */
    private $key;

    public function __construct(ClientFactory $clientFactory, string $domain, string $key)
    {
        $this->clientFactory = $clientFactory;
        $this->domain = $domain;
        $this->key = $key;
    }

    public function get(string $what, array $arguments = null): SimpleXMLElement
    {
        $message = $this->clientFactory->getClient()->request(
            'GET',
            $this->createUri(),
            [
                'query' => $this->createDirectRequest(['get' => 1, 'what' => $what], $arguments),
            ]
        );

        return $this->createXml($message);
    }

    public function post(string $what, string $xml): SimpleXMLElement
    {
        $message = $this->clientFactory->getClient()->request(
            'POST',
            $this->createUri(),
            [
                'form_params' => $this->createDirectRequest(
                    ['put' => 1, 'what' => $what, 'xmldata' => $xml]
                ),
            ]
        );

        return $this->createXml($message);
    }

    private function createUri(): string
    {
        return "https://login.directo.ee/xmlcore/{$this->domain}/xmlcore.asp";
    }

    private function createDirectRequest(array $array, array $arguments = null): array
    {
        $return = $array;
        $return['key'] = $this->key;
        $reserved = ['get', 'what', 'key'];
        if ($arguments) {
            foreach ($reserved as $item) {
                if (key_exists($item, $arguments)) {
                    $message = "Can not use reserved '$item' as argument key";
                    throw new LogicException($message);
                }
            }
            $return += $arguments;
        }

        return $return;
    }

    private function createXml(MessageInterface $string): SimpleXMLElement
    {
        $xml = simplexml_load_string($string->getBody());
        if (!$xml instanceof SimpleXMLElement) {
            throw new ServiceException('Directo did not respond');
        }
        if ('error' === $xml->getName()) {
            $message = (string)$xml['desc'];
            $code = (int)$xml['code'];
            throw new ServiceException($message, $code);
        }

        return $xml;
    }
}
