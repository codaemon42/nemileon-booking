<?php

namespace ONSBKS_Slots\RestApi\Controllers;

use WP_REST_Request;

class SlotTemplates
{

    public static function find_all(WP_REST_Request $request): void
    {
        try {
//            $body = $request->get_json_params();
//            $template = $body["template"];
            $slot_template = new \ONSBKS_Slots\Includes\Admin\SlotTemplates();
            wp_send_json(prepare_result($slot_template->find_all()));
        } catch (\Error $error) {
            wp_send_json(prepare_result(false, $error->getMessage(), false), 500);
        }
    }

    public static function create(WP_REST_Request $request): void
    {
        try {
            $body = $request->get_json_params();
            $template = $body["template"];
            $slot_template = new \ONSBKS_Slots\Includes\Admin\SlotTemplates();
            wp_send_json(prepare_result($slot_template->create($template)));
        } catch (\Error $error) {
            wp_send_json(prepare_result(false, $error->getMessage(), false), 500);
        }
    }
}