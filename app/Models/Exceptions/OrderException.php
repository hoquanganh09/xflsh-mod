<?php

namespace App\Models\Exceptions;

/**
 * Class OrderException
 */
class OrderException extends \Exception
{
    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
    }
}
