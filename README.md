# Symfony toolset

Various tools used in Symfony projects

## Tools
### AbstractDoctrineTestCase
Class for testing doctrine model and definitions.
Usage:
```
class ModelTest extends AbstractDoctrineTestCase
{
    public function testName()
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
## Developing
### Running code fixer

Run php cs fixer `./vendor/bin/php-cs-fixer fix`

### Running the tests

Run tests with phpunit `./vendor/bin/phpunit`

### Running analyzer

Run phan `./vendor/bin/phan`