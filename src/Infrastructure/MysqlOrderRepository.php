<?php

class MysqlOrderRepository implements OrderRepository
{
    public function create(OrderEntity $orderEntity)
    {
        $order = ORM::for_table('order')->create();
        $order->id       = $orderEntity->id;
        $order->store_id = $orderEntity->storeId;

        $order->save();

        foreach ($orderEntity->orderLines as $orderLineEntity) {
            $orderLine = ORM::for_table('order_line')->create();
            $orderLine->id       = $orderLineEntity->id;
            $orderLine->order_id = $orderEntity->id;
            $orderLine->sku      = $orderLineEntity->sku;
            $orderLine->store_id = $orderEntity->storeId;

            $orderLine->save();
        }
    }

    public function orderExistsInStore(OrderEntity $orderEntity)
    {
        $order = ORM::for_table('order')
            ->where('id', $orderEntity->id)
            ->where('store_id', $orderEntity->storeId)
            ->find_one();

        return ($order !== false);
    }

    public function existsStore(OrderEntity $orderEntity)
    {
        $store = ORM::for_table('store')
            ->where('id', $orderEntity->storeId)
            ->find_one();

        return ($store !== false);
    }
}