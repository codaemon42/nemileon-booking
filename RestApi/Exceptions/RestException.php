<?php

namespace ONSBKS_Slots\RestApi\Exceptions;

use WpOrg\Requests\Exception;

class RestException extends Exception
{

    public function __construct($message, $type, $data = null, $code = 0)
    {
        $response = [
            'success' => false,
            'data' => $data
        ];
        parent::__construct($message, $type, $response, $code);

    }
}