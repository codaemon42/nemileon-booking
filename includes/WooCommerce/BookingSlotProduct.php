<?php

namespace ONSBKS_Slots\Includes\WooCommerce;
use WC_Product_Simple;

class BookingSlotProduct extends WC_Product_Simple
{

    public $meta_prefix = 'NML_';

    /**
     * Initialize simple product.
     *
     * @param WC_Product_Simple|int $product Product instance or ID.
     */
    public function __construct($product = 0)
    {
//        $this->supports[] = 'ajax_add_to_cart';
        $this->product_type = 'booking_slot';
        parent::__construct($product);


    }



    /**
     * Get internal type.
     *
     * @return string
     */
    public function get_type()
    {
        return 'booking_slot';
    }

    public function get_booking_templates(){
        global $wpdb;

        $sql_query = $wpdb->prepare(
            "SELECT meta_id as id, post_id as product_id, meta_key as `key`, meta_value as template FROM {$wpdb->postmeta} WHERE post_id = %s AND meta_key LIKE %s",
            $this->get_id(), $this->meta_prefix . '%'
        );
        $results = $wpdb->get_results($sql_query, ARRAY_A);

        if (!empty($results)) {
            foreach ($results as $key => $result) {
                $results[$key]['template'] = maybe_unserialize($result['template']);
            }
        }

        return $results;
    }


    public function set_booking_template($meta_key, $meta_value){
        $exploded_meta_key = explode("_", $meta_key);
        if($exploded_meta_key[0]."_" != $this->meta_prefix){
            // NML_ prefix is not set from the frontend, safety purpose
            $meta_key = $this->meta_prefix . $meta_key;
        }

        return update_post_meta($this->get_id(), $meta_key, $meta_value);
    }

    /**
     * Returns all data for this object without the meta.
     *
     * @since  2.6.0
     * @return array
     */
    public function get_data_without_metas() {
        return array_merge( array( 'id' => $this->get_id() ), $this->data );
    }


}