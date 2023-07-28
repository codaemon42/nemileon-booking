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

        // Prepare the SQL query
        $sql_query = $wpdb->prepare(
            "SELECT meta_key, meta_value FROM {$wpdb->postmeta} WHERE meta_key LIKE %s",
            $this->meta_prefix . '%'
        );

        // Run  the query
        return $wpdb->get_results($sql_query, ARRAY_A);
    }


    public function set_booking_template($meta_key, $meta_value){
        $exploded_meta_key = explode("_", $meta_key);
        if($exploded_meta_key[0]."_" != $this->meta_prefix){
            // NML_ prefix is not set from the frontend, safety purpose
            $meta_key = $this->meta_prefix . $meta_key;
        }
        return $this->update_meta_data($meta_key, $meta_value);
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