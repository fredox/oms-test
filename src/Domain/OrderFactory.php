<?php


class OrderFactory
{
    public static function buildFromJson($jsonOrderInfo)
    {
        $orderInfo  = json_decode($jsonOrderInfo, true);

        if (json_last_error() != JSON_ERROR_NONE) {
            $orderCreationException = new CreateOrderException("Error on build from Json");
            $orderCreationException->addError('Invalid Json');
            throw $orderCreationException;
        }

        $orderLines = [];

        self::checkJson($orderInfo);

        foreach ($orderInfo['order']['lines'] as $line) {
            $orderLines[] = new OrderLineEntity($line['line_number'], $line['sku']);
        }

        $orderEntity = new OrderEntity($orderInfo['order']['id'], $orderInfo['order']['store_id'], $orderLines);

        return $orderEntity;
    }

    public static function checkJson($orderInfo)
    {
        if (!array_key_exists('order', $orderInfo)) {
            self::handleErrors(['field order is mandatory']);
        }

        $errorMessages = self::requireFields(['id', 'store_id', 'lines'], $orderInfo['order']);
        $errorMessages = array_merge($errorMessages, self::numericFields(['id', 'store_id'], $orderInfo['order']));

        if (empty($orderInfo['order']['lines'])) {
            $errorMessages[] = 'Field lines is mandatory. At least one line must be provided';
            self::handleErrors($errorMessages);

        }

        foreach ($orderInfo['order']['lines'] as $orderLine) {
            $errorMessages = array_merge($errorMessages, self::requireFields(['line_number', 'sku'], $orderLine));
            $errorMessages = array_merge($errorMessages, self::numericFields(['line_number'], $orderLine));
        }

        if (!empty($errorMessages)) {
            self::handleErrors($errorMessages);
        }
    }

    public static function requireFields($fields, $data)
    {
        $errorMessages = [];

        foreach ($fields as $field) {
            if (!array_key_exists($field, $data)) {
                $errorMessages[] = 'Field ' . $field . ' is mandatory. ';
            }
        }

        return $errorMessages;
    }

    public static function numericFields($fields, $data)
    {
        $errorMessages = [];

        foreach ($fields as $field) {
            if (!empty($data[$field]) && !is_numeric($data[$field])) {
                $errorMessages[] = 'Field ' . $field . ' must be numeric. ';
            }
        }

        return $errorMessages;
    }

    public static function handleErrors($errors)
    {
        $exception = new CreateOrderException('Order could not be created', 400);
        $exception->setErrors($errors);

        throw $exception;
    }
}