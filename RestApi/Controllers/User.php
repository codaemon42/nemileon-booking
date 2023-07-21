<?php

namespace ONSBKS_Slots\RestApi\Controllers;

class User
{

    public static function signin(\WP_REST_Request $request)
    {
        $body = $request->get_body_params();
        $username = $body['username'];
        $password = $body['password'];
    }
}