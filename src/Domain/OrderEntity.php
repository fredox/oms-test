<?php

class OrderEntity
{
    public $id;
    public $storeId;
    /** @var OrderLineEntity[] */
    public $orderLines;

    public function __construct($id, $storeId, $orderLines)
    {
        $this->id = $id;
        $this->storeId = $storeId;
        $this->orderLines = $orderLines;
    }
}