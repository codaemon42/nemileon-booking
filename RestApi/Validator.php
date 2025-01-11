<?php

namespace ONSBKS_Slots\RestApi;

class Validator
{

    public static function validateQueryParams($params, $required_arr): void
    {
        $result = [];
        foreach ($required_arr as $key) {
            if (!self::isValid($params, $key)) {
                $result[] = "$key field is required";
            }
        }
        if(count($result) > 0) {
            wp_send_json(onsbks_prepare_result($result, implode(", ", $result), false), 400);
        }
    }

    public static function isValid($params, $key): bool
    {
        return isset($params[$key]) &&
            ($params[$key] !== '') &&
            ((is_string($params[$key]) ? trim($params[$key]) : $params[$key]) !== '' || $params[$key] === 0);
    }
}