<?php

namespace ONSBKS_Slots\RestApi\Exceptions;

class BookingNotAllowedException extends RestException
{
    public function __construct($message = "Booking Not allowed")
    {
        parent::__construct($message, 'BOOKING', onsbks_prepare_result(false, $message, false), 500);
    }
}