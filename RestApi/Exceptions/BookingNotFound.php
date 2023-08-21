<?php

namespace ONSBKS_Slots\RestApi\Exceptions;

use WpOrg\Requests\Exception;

class BookingNotFound extends Exception
{
    public function __construct($message = "Booking Not Found")
    {
        parent::__construct($message, 'BOOKING', prepare_result(false, $message, false), 500);
    }
}