# DoctrineSubscriberTrait
Trait for helping test Doctrine subscriber.

Usage:
```
class MyServiceTest extends WebTestCase
{
    use DoctrineSubscriberTrait;

    public function testContainerService(): void
    {
        $this->doTestSubscriber(
            Subscriber::class,
            [
                'prePersist',
                'postRemove',
                'postFlush',
            ]
        );

    }
}
```