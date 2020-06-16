# Symfony toolset

Various tools used in Symfony projects

## Tools
### Testing
* [Class for testing doctrine model and definitions](./docs/AbstractDoctrineTestCase.md)
* [Creating and filling objects with setters](./docs/ModelTestTrait.md)
* [Storing and reloading doctrine objects](./docs/DoctrineTestTrait.md)
* [Creating units with multiple constructor arguments](./docs/CreateServiceTrait.md)
## Developing
### Running code fixer

Run php cs fixer `./vendor/bin/php-cs-fixer fix`

### Running the tests

Run tests with phpunit `./vendor/bin/phpunit`

### Running analyzer

Run phan `./vendor/bin/phan`