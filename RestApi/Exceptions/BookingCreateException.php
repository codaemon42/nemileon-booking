<?php

namespace ONSBKS_Slots\RestApi\Exceptions;

class BookingCreateException extends RestException
{
    public function __construct($message = "Failed to create booking, contact support")
    {
        parent::__construct($message, 'BOOKING', onsbks_prepare_result(false, $message, false), 500);
    }
}