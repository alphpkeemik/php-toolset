# ModelTestTrait
Trait for automatic (Doctrine model) object creation.
Usage:
```
class MyModelTest extends TestCase
{
    use ModelTestTrait;

    public function testName(): void
    {
         $name = uniqid();
         $hobbies = [uniqid()];
         // person is class with email, name, phone, hobbies
         // name and hobbies are set custom
         // email, phone is filled by trait
         $model = $this->createEntity(Person::class, [
            'setName' => $name,
            'setHobbies' => [$hobbies]
         ]);
    }
}
```