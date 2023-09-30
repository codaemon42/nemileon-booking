<?php

namespace ONSBKS_Slots\RestApi\Exceptions;

class OptionNotFound extends RestException
{
    public function __construct($message = "Option Not Found")
    {
        parent::__construct($message, 'OPTIONS', onsbks_prepare_result(false, $message, false), 400);
    }
}
