<?php

namespace ONSBKS_Slots\RestApi;

use \WP_REST_Request;

class Middleware
{



    public static function Anonymous(WP_REST_Request $request): bool
    {
        $token = $request->get_header('jwt');
        if($token == null) return false;

        $result = onsbks_decode_jwt($token);

        if(count($result) == 0) {
            return false;
        }

        if($result['anonymous']) {
            return wp_verify_nonce($result['nonce'], 'JWT_NONCE');
        }

        $request->set_header('user_id', $result['user_id']);
        $request->set_header('anonymous', $result['anonymous']);

        return true;
    }


    public static function Admin(WP_REST_Request $request): bool
    {
        $token = $request->get_header('jwt');

        $result = onsbks_decode_jwt($token);

        if(count($result) == 0) {
            return false;
        }

        if($result['anonymous']) {
            return false;
        }

        $request->set_header('user_id', $result['user_id']);
        $request->set_header('anonymous', $result['anonymous']);

        return user_can($result['user_id'], 'manage_options');
    }

    public static function Editor(WP_REST_Request $request): bool
    {
        $token = $request->get_header('jwt');

        $result = onsbks_decode_jwt($token);

        if(count($result) == 0) {
            return false;
        }

        if($result['anonymous']) {
            return false;
        }

        $request->set_header('user_id', $result['user_id']);
        $request->set_header('anonymous', $result['anonymous']);

        return user_can($result['user_id'], 'edit_others_posts');
    }

    public static function User(WP_REST_Request $request): bool
    {
        $token = $request->get_header('jwt');

        $result = onsbks_decode_jwt($token);

        if(count($result) == 0) {
            return false;
        }

        if($result['anonymous']) {
            return false;
        }

        $request->set_header('user_id', $result['user_id']);
        $request->set_header('anonymous', $result['anonymous']);

        return user_can($result['user_id'], 'edit_posts');
    }

}

