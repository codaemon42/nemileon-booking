<?php

namespace ONSBKS_Slots\RestApi\Controllers;


use ONSBKS_Slots\Includes\WooCommerce\BookingSlotProduct;
use WP_REST_Request;

class Product
{

    public static function get_products(WP_REST_Request $request){
        try {
            $products_array_object = onsbks_get_products();
            $products = [];
            foreach ($products_array_object as $product){
                $product = new BookingSlotProduct($product);
                $p = $product->get_data();
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


    public static function set_products_meta(WP_REST_Request $request){
        try{
            $query_params = $request->get_json_params();
            $product_id = $query_params['product_id'];
            $key = $query_params['key'];
            $value = $query_params['value'];
            $result = update_post_meta($product_id, $key, $value);
            wp_send_json(prepare_result($result));
        } catch (\Error $error) {
            wp_send_json(prepare_result(false, $error->getMessage(), false), 500);
        }
    }
}