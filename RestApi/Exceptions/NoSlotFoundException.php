<?php

namespace ONSBKS_Slots\RestApi\Exceptions;

class NoSlotFoundException extends RestException
{
    public function __construct($message = "No valid slot found")
    {
        parent::__construct($message, 'BOOKING', prepare_result(false, $message, false), 404);
    }
}