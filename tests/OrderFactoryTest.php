<?php

include __DIR__ . '/../src/Domain/OrderFactory.php';

class OrderFactoryTest extends PHPUnit\Framework\TestCase
{

    public function testFromJsonHappyPath()
    {
        /** @var OrderFactory $factoryService */
        $factoryService = new OrderFactory();

        $json = '{
          "order": {
            "id": 2,
            "store_id": 4,
            "lines": [
              {
                "line_number": 1,
                "sku": "blue_sock"
              },
              {
                "line_number": 2,
                "sku": "red_sock"
              }
            ]
          }
        }';

        $orderEntity = $factoryService->buildFromJson($json);

        $this->assertEquals(2, $orderEntity->id);
        $this->assertEquals(4, $orderEntity->storeId);
        $this->assertCount(2, $orderEntity->orderLines);
        $this->assertEquals(1, $orderEntity->orderLines[0]->id);
        $this->assertEquals('blue_sock', $orderEntity->orderLines[0]->sku);
        $this->assertEquals(2, $orderEntity->orderLines[1]->id);
        $this->assertEquals('red_sock', $orderEntity->orderLines[1]->sku);
    }

    /**
     * @test
     * @expectedException CreateOrderException
     */
    public function testFromJsonInvalidJson()
    {
        /** @var OrderFactory $factoryService */
        $factoryService = new OrderFactory();

        $json = '{
          "order": {
            "id": 2,
            "store_id": 4,
            "lines": [
              {
                "line_number": 1,
                "sku": "blue_sock"
              },
              {
                "line_number": 2,
                "sku": "red_sock"
              },
              {
                "line_number": 3,
                "sku": "red_sock"
         ';

        $factoryService->buildFromJson($json);
    }

    /**
     * @test
     * @expectedException CreateOrderException
     */
    public function testFromJsonMissingFields()
    {
        /** @var OrderFactory $factoryService */
        $factoryService = new OrderFactory();

        $json = '{
          "order": {
            "id": 2,
            "lines": [
              {
                "sku": "blue_sock"
              },
              {
                "line_number": 2,
                "sku": "red_sock"
              },
              {
                "line_number": 3,
                "sku": "red_sock"
              }
            ]
          }
        }';

        $factoryService->buildFromJson($json);
    }

    /**
     * @test
     * @expectedException CreateOrderException
     */
    public function testFromJsonFormatFields()
    {
        /** @var OrderFactory $factoryService */
        $factoryService = new OrderFactory();

        $json = '{
          "order": {
            "id": "wrong-id",
            "lines": [
              {
                "sku": "blue_sock"
              },
              {
                "line_number": 2,
                "sku": "red_sock"
              },
              {
                "line_number": 3,
                "sku": "red_sock"
              }
            ]
          }
        }';

        $factoryService->buildFromJson($json);
    }

    /**
     * @test
     * @expectedException CreateOrderException
     */
    public function testFromJsonLinesEmpty()
    {
        /** @var OrderFactory $factoryService */
        $factoryService = new OrderFactory();

        $json = '{
          "order": {
            "id": 2,
            "lines": []
          }
        }';

        $factoryService->buildFromJson($json);
    }
}