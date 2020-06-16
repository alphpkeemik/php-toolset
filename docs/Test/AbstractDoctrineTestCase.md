# AbstractDoctrineTestCase
Class for testing doctrine model and definitions.

Usage:
```
class ModelTest extends AbstractDoctrineTestCase
{
    public function testName(): void
    {
        $em = $this->createManager(Model::class);
        $test = new Model();
        $test->set...(uniqid());
        $em->persist($test);
        $em->flush();
        $this->assertTrue(true);
    }
}
```