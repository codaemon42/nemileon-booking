<?php

namespace ONSBKS_Slots\RestApi\Controllers;


use ONSBKS_Slots\Includes\WooCommerce\BookingSlotProduct;
use ONSBKS_Slots\RestApi\Validator;
use WP_REST_Request;

class Product
{

    public static function get_products(WP_REST_Request $request){
        try {
            $products_array_object = onsbks_get_products();
            $products = [];
            foreach ($products_array_object as $product){
                $product = new BookingSlotProduct($product);
                $p = $product->get_data_without_metas();
                $p['imageUrl'] = $p['image_id'] ? wp_get_attachment_image_url($p['image_id']) : false;
                $p['gallery_images'] = [];
                foreach ($p['gallery_image_ids'] as $gallery_image_id){
                    $p['gallery_images'][] = wp_get_attachment_image_url($gallery_image_id);
                }
                array_push($products, $p);
            }
            wp_send_json(prepare_result($products));
        } catch (\Error $error) {
            wp_send_json(prepare_result(false, $error->getMessage(), false), 500);
        }
    }

    public static function get_products_meta(WP_REST_Request $request){
        try{
            $query_params = $request->get_query_params();
            $product_id = $query_params['product_id'];
            $key = $query_params['key'];
            $result = get_post_meta($product_id, $key, true);
            wp_send_json(prepare_result($result));
        } catch (\Error $error) {
            wp_send_json(prepare_result(false, $error->getMessage(), false), 500);
        }
    }


    /**
     * @param WP_REST_Request
     * @query_param `product_id`
     * @query_param `key` comma separated
     * @query_param `value` the slot template json
     */
    public static function set_booking_template(WP_REST_Request $request){
        try{
            $query_params = $request->get_json_params();

            Validator::validate_query_parmas($query_params, ['product_id', 'key', 'value']);

            $product_id = $query_params['product_id'];
            $keys = $query_params['key'];
            $value = $query_params['value'];
            $exploded_key = explode(",", $keys);

            $product = new BookingSlotProduct($product_id);

            foreach ($exploded_key as $key){
                $product->set_booking_template($key, $value);
            }
            $result = true;

            wp_send_json(prepare_result($result));
        } catch (\Error $error) {
            wp_send_json(prepare_result(false, $error->getMessage(), false), 500);
        }
    }

    public static function get_booking_templates(WP_REST_Request $request){
        try{
            $query_params = $request->get_query_params();

            Validator::validate_query_parmas($query_params, ['product_id']);

            $product_id = $query_params['product_id'];
            $product = new BookingSlotProduct($product_id);
            $result = $product->get_booking_templates();

            wp_send_json(prepare_result($result));
        } catch (\Error $error) {
            wp_send_json(prepare_result(false, $error->getMessage(), false), 500);
        }
    }
}