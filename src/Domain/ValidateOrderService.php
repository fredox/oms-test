<?php

class ValidateOrderService
{
    /** @var OrderRepository */
    public $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function validate(OrderEntity $orderEntity)
    {
        $this->validateLinesInSequence($orderEntity);
        $this->validateStoreExists($orderEntity);
        $this->validateOrderUniqueInStore($orderEntity);
    }

    public function validateLinesInSequence(OrderEntity $orderEntity)
    {
        $sequence = 0;

        foreach ($orderEntity->orderLines as $orderLine) {
            if ($orderLine->id != ($sequence + 1)) {
                $orderException = new CreateOrderException('Error on validate line number', 400);
                $orderException->addError('Lines number must be sequential, starting at 1');

                throw $orderException;
            }

            $sequence++;
        }

        return true;
    }

    public function validateOrderUniqueInStore(OrderEntity $orderEntity)
    {
        if ($this->orderRepository->orderExistsInStore($orderEntity)) {
            $orderException = new CreateOrderException('Error on validate Order Entity', 400);
            $orderException->addError('Order with id: [' . $orderEntity->id . '] it already exists in store with id: [' . $orderEntity->storeId . ']');

            throw $orderException;
        }
    }

    public function validateStoreExists(OrderEntity $orderEntity)
    {
        if (!$this->orderRepository->existsStore($orderEntity)) {
            $orderException = new CreateOrderException('Error on validate Store', 400);
            $orderException->addError('Store with id: [' . $orderEntity->storeId . '] does not exist.');

            throw $orderException;
        }
    }
}