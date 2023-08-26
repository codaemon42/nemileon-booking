<?php

namespace ONSBKS_Slots\RestApi\Exceptions;

class BookingProcessException extends RestException
{
    public function __construct($message = "Error occurred while processing booking")
    {
        parent::__construct($message, 'BOOKING', prepare_result(false, $message, false), 500);
    }
}