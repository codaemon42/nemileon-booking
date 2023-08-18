<?php
namespace ONSBKS_Slots\RestApi\Exceptions;

use WpOrg\Requests\Exception;

class NotBookableException extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message, 'BOOKING', prepare_result(false, $message, false), 500);
    }
}