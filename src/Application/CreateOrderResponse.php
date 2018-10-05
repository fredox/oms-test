<?php

class CreateOrderResponse
{
    /** @var OrderEntity */
    public $orderEntity;

    public function OrderEntityToArray()
    {
        $jsonOrderArray = [];

        $jsonOrderArray['id']       = $this->orderEntity->id;
        $jsonOrderArray['store_id'] = $this->orderEntity->storeId;
        $jsonOrderArray['lines']    = [];

        foreach ($this->orderEntity->orderLines as $orderLine) {
            $orderLine = [
                'line_number' => $orderLine->id,
                'sku'         => $orderLine->sku
            ];

            $jsonOrderArray['lines'][] = $orderLine;
        }

        return $jsonOrderArray;
    }

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