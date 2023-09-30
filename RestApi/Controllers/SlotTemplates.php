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
            $slot_template = new \ONSBKS_Slots\Includes\Entities\SlotTemplates(false);
            wp_send_json(onsbks_prepare_result($slot_template->find_all()));
        } catch (\Error $error) {
            wp_send_json(onsbks_prepare_result(false, $error->getMessage(), false), 500);
        }
    }

    public static function create(WP_REST_Request $request): void
    {
        try {
            $body = $request->get_json_params();
            $name = $body["name"];
            $template = $body["template"];
            $slot_template = new \ONSBKS_Slots\Includes\Entities\SlotTemplates(false);
            wp_send_json(onsbks_prepare_result($slot_template->create($template, $name)));
        } catch (\Error $error) {
            wp_send_json(onsbks_prepare_result(false, $error->getMessage(), false), 500);
        }
    }


    public static function update(WP_REST_Request $request): void
    {
        try {
            $body = $request->get_json_params();
            $id= $body["id"];
            $name = $body["name"];
            $template = $body["template"];
            $slot_template = new \ONSBKS_Slots\Includes\Entities\SlotTemplates(false);
            wp_send_json(onsbks_prepare_result($slot_template->update($id, $name, $template)));
        } catch (\Error $error) {
            wp_send_json(onsbks_prepare_result(false, $error->getMessage(), false), 500);
        }
    }

    public static function delete(WP_REST_Request $request): void
    {
        try {
            $query_params= $request->get_query_params();
            $id = $query_params["id"];
            $slot_template = new \ONSBKS_Slots\Includes\Entities\SlotTemplates(false);
            wp_send_json(onsbks_prepare_result($slot_template->delete($id)));
        } catch (\Error $error) {
            wp_send_json(onsbks_prepare_result(false, $error->getMessage(), false), 500);
        }
    }
}