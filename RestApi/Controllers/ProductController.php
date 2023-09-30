<?php

namespace ONSBKS_Slots\RestApi\Controllers;


use ONSBKS_Slots\Includes\WooCommerce\BookingSlotProduct;
use ONSBKS_Slots\RestApi\Exceptions\NoSlotFoundException;
use ONSBKS_Slots\RestApi\Services\ProductService;
use ONSBKS_Slots\RestApi\Validator;
use PHPUnit\Exception;
use WP_REST_Request;

class ProductController
{

    private ProductService $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    public static function get_products(WP_REST_Request $request): void
    {
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
                $products[] = $p;
            }
            wp_send_json(onsbks_prepare_result($products));
        } catch (\Error $error) {
            wp_send_json(onsbks_prepare_result(false, $error->getMessage(), false), 500);
        }
    }

    public function get_products_meta(WP_REST_Request $request): void
    {
        try{
            $query_params = $request->get_query_params();
            $productId = $query_params['product_id'];
            $key = $query_params['key'];
            $slot = $this->productService->findProductTemplate($productId, $key, true);
            wp_send_json(onsbks_prepare_result($slot->getData()));
        } catch (\Error $error) {
            wp_send_json(onsbks_prepare_result(false, $error->getMessage(), false), 500);
        } catch (\Exception $e) {
            wp_send_json(onsbks_prepare_result(false, $e->getMessage(), false), 500);
        }
    }

    public static function get_booking_templates(WP_REST_Request $request){
        try{
            $query_params = $request->get_query_params();

            Validator::validate_query_parmas($query_params, ['product_id']);

            $product_id = $query_params['product_id'];
            $product = new BookingSlotProduct($product_id);
            $results = $product->get_booking_templates();
//            $ns = $results;
//            foreach ($results as $result) {
//                $n = $result;
//                $n['template'] = unserialize($result['template']);
//                array_push($ns, $n);
//            }

            wp_send_json(onsbks_prepare_result($results));
        } catch (\Error $error) {
            wp_send_json(onsbks_prepare_result(false, $error->getMessage(), false), 500);
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

            Validator::validate_query_parmas($query_params, ['product_id', 'key', 'template']);

            $product_id = $query_params['product_id'];
            $keys = $query_params['key'];
            $value = $query_params['template'];
            $exploded_key = explode(",", $keys);

            $product = new BookingSlotProduct($product_id);

            $result = [];
            foreach ($exploded_key as $key){
                $savedTemplate = $product->set_booking_template($key, $value);
                $result[$key] = $savedTemplate;
            }

            wp_send_json(onsbks_prepare_result($result));
        } catch (\Error $error) {
            wp_send_json(onsbks_prepare_result(false, $error->getMessage(), false), 500);
        }
    }
}