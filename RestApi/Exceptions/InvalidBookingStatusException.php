<?php

namespace ONSBKS_Slots\RestApi\Exceptions;

class InvalidBookingStatusException extends RestException
{
    public function __construct($message = "Invalid Booking Status")
    {
        parent::__construct($message, 'BOOKING', onsbks_prepare_result(false, $message, false), 500);
    }
}
