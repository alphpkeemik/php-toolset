# GuzzleMockTrait
Trait for GuzzleHttp\ClientFactory mock creation.

Usage:
```
class MyServiceTest extends TestCase
{
    use GuzzleMockTrait;

    public function testName(): void
    {
           $clientFactory = $this->createClientFactoryMock(
                $method, $uri, $options, $response
            );
    }
}
```