# Directo

Simple Directo API

Usage:
```
class MyService
{

    /**
     * @var DirectoService
     */
    private $directoService;

    public function execute(): void
    {
         $xml = $this->directoService->get('purchaseorder', ['ts' => '20.05.2021']);
    }
}
```