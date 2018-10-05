<?php

class CreateOrderException extends Exception
{
    public $errors;

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param mixed $errors
     */
    public function setErrors($errors)
    {
        foreach ($errors as $error) {
            $this->addError($error);
        }
    }

    public function addError($error)
    {
        App::$log->error('Exception occurred on create order:' . $error);
        $this->errors[] = $error;
    }
}