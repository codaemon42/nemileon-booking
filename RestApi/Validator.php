<?php

namespace ONSBKS_Slots\RestApi;

class Validator
{

    public static function validate_query_parmas($params, $required_arr){
        $result = [];
        foreach ($required_arr as $key) {
            if (isset($params[$key]) && ($params[$key] !== '') && (trim($params[$key]) !== '' || $params[$key] === 0)) {

            } else {
                array_push($result, "$key field is required");
            }
        }
        if(count($result) > 0) {
            wp_send_json(prepare_result($result, implode(", ", $result), false), 400);
        }
    }
}