<?php

namespace ONSBKS_Slots\RestApi\Exceptions;

class BookingFailedException extends RestException
{
    public function __construct($message = "Booking Failed")
    {
        parent::__construct($message, 'BOOKING', onsbks_prepare_result(false, $message, false), 500);
    }
}