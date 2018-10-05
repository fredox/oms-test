<?php

interface OrderRepository
{
    public function create(OrderEntity $orderEntity);
    public function orderExistsInStore(OrderEntity $orderEntity);
    public function existsStore(OrderEntity $orderEntity);
}