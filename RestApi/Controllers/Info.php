<?php

namespace ONSBKS_Slots\RestApi\Controllers;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Info
{


    public static function check_info(\WP_REST_Request $request){
        wp_send_json(
            array('result' => array(
                'version' => ONSBKS_VERSION,
                'jwt' => $request->get_header('jwt')),
                'verify' => JWT::decode($request->get_header('jwt'), new Key(onsbks_get_jwt_secret(), 'HS256'))
            )
        );
    }
}