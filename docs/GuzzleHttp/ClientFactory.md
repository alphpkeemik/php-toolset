# ClientFactory
Guzzle client creation with logger.

Usage:
```
class MyService
{

    /**
     * @var ClientFactory
     */
    private $clientFactory;

    public function execute(): void
    {
         $this->clientFactory->getClient()->...;
    }
}
```