# DoctrineTestTrait
Trait for storing and reloading doctrine objects.

Usage:
```
class ModelTest extends AbstractDoctrineTestCase
{
    use DoctrineTestTrait;
    use ModelTestTrait;

    public function testName(): void
    {
         
         $personLocal = $this->createEntity(Person::class);
         $personDb = $this->persistEntity(Person::class);
         // person model is reloaded from db by DoctrineTestTrait
         // lets see if orm config is configured correctly
         $this->assertSame($personLocal->getName(), $personDb->getName());
    }
}
```