<?php

namespace ONSBKS_Slots\RestApi\Exceptions;

class ConversionException extends RestException
{
    public function __construct($message = "Conversion Error")
    {
        parent::__construct($message, 'BOOKING', onsbks_prepare_result(false, $message, false), 500);
    }
}