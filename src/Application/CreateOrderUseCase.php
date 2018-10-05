<?php

class CreateOrderUseCase
{
    /** @var CreateOrderService $createOrderService */
    public $createOrderService;

    /** @var CreateOrderResponse $createOrderResponse */
    public $createOrderResponse;


    public function __construct(CreateOrderService $createOrderService)
    {
        $this->createOrderService  = $createOrderService;
    }

    /**
     * @param CreateOrderRequest $createOrderRequest
     */
    public function execute(CreateOrderRequest $createOrderRequest)
    {
        $orderEntity = $createOrderRequest->getOrderEntity();
        $this->createOrderResponse->setOrderEntity(
            $this->createOrderService->createOrder($orderEntity)
        );

        BaseController::putResponse(BaseController::SUCCESS_STATUS, $this->createOrderResponse->getOrderEntity());
    }
}