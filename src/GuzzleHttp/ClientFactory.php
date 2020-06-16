<?php

namespace Ambientia\Toolset\GuzzleHttp;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Log\LoggerInterface;

/**
 * @author mati.andreas@ambientia.ee
 */
class ClientFactory
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getClient(): Client
    {
        $stack = HandlerStack::create();

        $stack->push(
            Middleware::log(
                $this->logger,
                new MessageFormatter(MessageFormatter::DEBUG)
            )
        );

        return new Client([
            'handler' => $stack,
            'http_errors' => true,
        ]);
    }
}
