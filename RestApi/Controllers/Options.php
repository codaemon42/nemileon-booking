<?php

namespace ONSBKS_Slots\RestApi\Controllers;

use WP_REST_Request;

class Options
{

    public static function set_option(WP_REST_Request $request){
        try {
            $body = $request->get_json_params();
            $option_name = $body["key"];
            $option_value = $body['value'];
            $response = update_option($option_name, $option_value);
            wp_send_json(prepare_result($response));
        } catch (\Error $error) {
            wp_send_json(prepare_result(false, $error->getMessage(), false), 500);
        }
    }

    public static function get_option(WP_REST_Request $request){
        try {
            $key = 'key';
            $query_params = $request->get_query_params();
            $response = get_option($query_params[$key]);
            wp_send_json(prepare_result($response));
        } catch (\Error $error) {
            wp_send_json(prepare_result(false, $error->getMessage(), false), 500);
        }
    }
}