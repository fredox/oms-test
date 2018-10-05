<?php

Class CreateOrderRequest
{
    /** @var OrderEntity $orderEntity */
    public $orderEntity;

    /**
     * @return OrderEntity
     */
    public function getOrderEntity()
    {
        return $this->orderEntity;
    }

    /**
     * @param OrderEntity $orderEntity
     */
    public function setOrderEntity($orderEntity)
    {
        $this->orderEntity = $orderEntity;
    }
}