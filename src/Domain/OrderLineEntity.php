<?php

class OrderLineEntity
{
    public $id;
    public $sku;

    public function __construct($id, $sku)
    {
        $this->id     = $id;
        $this->sku    = $sku;
    }
}