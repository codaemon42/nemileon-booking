<?php

namespace ONSBKS_Slots\RestApi\Exceptions;

class OptionUpdateException extends RestException
{
    public function __construct($message)
    {
        parent::__construct($message, 'OPTIONS', onsbks_prepare_result(false, $message, false), 400);
    }
}
