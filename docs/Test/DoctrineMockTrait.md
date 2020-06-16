# DoctrineMockTrait
Trait for helping mock Doctrine manager registry.

Usage:
```
class MyServiceTest extends TestCase
{
    use DoctrineMockTrait;

    public function testName(): void
    {

        $dm = $this->createDoctrineMock(Person::class);
        // service contains code
        //    $repo = $this->doctrine->getManagerForClass(Person::class)
        //    ->getRepository(Person::class);
        // $person = $repo->findOneBy(['email' => $email]);
        $dm->getRepository()
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => $email])
            ->willReturn($person);

        $service = new MyService($dm->getDoctrine());

    }
}
```