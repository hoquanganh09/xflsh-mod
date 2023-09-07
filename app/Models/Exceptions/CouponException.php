<?php

namespace App\Models\Exceptions;

/**
 * Class CouponException
 */
class CouponException extends \Exception
{

    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
    }
}