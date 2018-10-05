<?php

class OrderController extends BaseController
{
    public function create()
    {
        try {
            /** @var CreateOrderUseCase $useCase */
            $useCase = App::$container['order.create.use-case'];
            $jsonOrderInfo = $this->getRequest();

            $createOrderRequest = new CreateOrderRequest();

            $orderEntity = OrderFactory::buildFromJson($jsonOrderInfo);
            $createOrderRequest->setOrderEntity($orderEntity);

            $useCase->createOrderResponse = new CreateOrderResponse();

            $useCase->execute($createOrderRequest);

        } Catch (CreateOrderException $e) {
            self::badRequest($e->getErrors());
        } Catch (Exception $e) {
            self::error($e->getMessage());
        }
    }
}