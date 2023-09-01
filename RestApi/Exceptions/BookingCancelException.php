<?php

namespace ONSBKS_Slots\RestApi\Exceptions;

class BookingCancelException extends RestException
{
    public function __construct($message = "Failed to cancel booking, contact support")
    {
        parent::__construct($message, 'BOOKING', prepare_result(false, $message, false), 500);
    }
}