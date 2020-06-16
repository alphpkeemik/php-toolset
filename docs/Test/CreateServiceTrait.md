# CreateServiceTrait
Trait for automatic object creation by injecting needed construction arguments.

Usage:
```
class MyServiceTest extends TestCase
{
    use CreateServiceTrait;

    public function testName(): void
    {

         $service = $this->createService(
            // first argument muts be class name
            // other argument order is not important
            MyService::class,

            // provide optional class / interface
             $this->createMock(Custom::class),
            // provide optional vars by argument name
            [
            'custom' => ...,
            ]
          );
         ....

    }
}
```