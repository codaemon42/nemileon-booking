<?php

namespace ONSBKS_Slots\RestApi\Exceptions;

class OptionNotFound extends RestException
{
    public function __construct($message = "Option Not Found")
    {
        parent::__construct($message, 'OPTIONS', prepare_result(false, $message, false), 400);
    }
}
