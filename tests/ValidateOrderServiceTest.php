<?php
include __DIR__ . '/../src/App.php';
include __DIR__ . '/../src/Domain/CreateOrderException.php';
include __DIR__ . '/../src/Domain/ValidateOrderService.php';
include __DIR__ . '/../src/Domain/OrderLineEntity.php';
include __DIR__ . '/../src/Domain/OrderEntity.php';
include __DIR__ . '/../src/Domain/OrderRepository.php';
include __DIR__ . '/../src/Infrastructure/MysqlOrderRepository.php';

require __DIR__ . '/../vendor/autoload.php';

App::initLog(false, true);

class ValidateOrderServiceTest extends PHPUnit\Framework\TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|OrderRepository */
    private $orderRepositoryMock;

    protected function setUp()
    {
        $this->orderRepositoryMock = $this->getOrderRepositoryMock();
    }

    /**
     * @test
     * @expectedException CreateOrderException
     */
    public function testValidateLinesInSequenceThrowsException()
    {
        $validationService = new ValidateOrderService($this->orderRepositoryMock);

        $orderLines = [
            new OrderLineEntity(1, 'sku-A'),
            new OrderLineEntity(3, 'sku-B'),
            new OrderLineEntity(2, 'sku-C')
        ];

        $orderEntity = new OrderEntity(1, 2, $orderLines);

        $validationService->validateLinesInSequence($orderEntity);
    }

    /**
     * @test
     */
    public function testValidateLinesInSequenceOk()
    {
        $validationService = new ValidateOrderService($this->orderRepositoryMock);

        $orderLines = [
            new OrderLineEntity(1, 'sku-A'),
            new OrderLineEntity(2, 'sku-B'),
            new OrderLineEntity(3, 'sku-C')
        ];

        $orderEntity = new OrderEntity(1, 2, $orderLines);

        $result = $validationService->validateLinesInSequence($orderEntity);

        $this->assertTrue($result);
    }

    /**
     * @test
     * @expectedException CreateOrderException
     */
    public function testValidateStoreExists()
    {
        $this->orderRepositoryMock
            ->method('existsStore')
            ->willReturn(false);

        $validationService = new ValidateOrderService($this->orderRepositoryMock);

        $orderLines = [
            new OrderLineEntity(1, 'sku-A'),
            new OrderLineEntity(2, 'sku-B'),
            new OrderLineEntity(3, 'sku-C')
        ];

        $orderEntity = new OrderEntity(1, 2, $orderLines);

        $validationService->validateStoreExists($orderEntity);
    }

    /**
     * @test
     * @expectedException CreateOrderException
     */
    public function testValidateOrderUniqueInStore()
    {
        $this->orderRepositoryMock
            ->method('orderExistsInStore')
            ->willReturn(true);

        $validationService = new ValidateOrderService($this->orderRepositoryMock);

        $orderLines = [
            new OrderLineEntity(1, 'sku-A'),
            new OrderLineEntity(2, 'sku-B'),
            new OrderLineEntity(3, 'sku-C')
        ];

        $orderEntity = new OrderEntity(1, 2, $orderLines);

        $validationService->validateOrderUniqueInStore($orderEntity);
    }

    private function getOrderRepositoryMock()
    {
        return $this->getMockBuilder(OrderRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}