<?php

namespace ONSBKS_Slots\RestApi\Exceptions;

class BookingFailedException extends \WpOrg\Requests\Exception
{
    public function __construct($message)
    {
        parent::__construct($message, 'BOOKING', prepare_result(false, $message, false), 500);
    }
}