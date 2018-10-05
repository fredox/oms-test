<?php

class CreateOrderService
{
    /** @var OrderRepository $orderRepository */
    public $orderRepository;

    /** @var ValidateOrderService $validateOrderService */
    public $validateOrderService;

    public function __construct(OrderRepository $orderRepository, ValidateOrderService $validateOrderService)
    {
        $this->orderRepository = $orderRepository;
        $this->validateOrderService = $validateOrderService;
    }

    public function createOrder(OrderEntity $orderEntity)
    {
        try {
            $this->validateOrderService->validate($orderEntity);
            $this->orderRepository->create($orderEntity);

            return $orderEntity;
        } Catch (Exception $e) {
            App::$log->error('Exception occurred on create order service:' . $e->getMessage());
            throw $e;
        }
    }
}