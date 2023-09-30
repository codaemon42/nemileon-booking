<?php

namespace ONSBKS_Slots\RestApi\Exceptions;

class NoSlotFoundException extends RestException
{
    public function __construct($message = "No valid slot found")
    {
        parent::__construct($message, 'BOOKING', onsbks_prepare_result(false, $message, false), 404);
    }
}