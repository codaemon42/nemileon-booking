<?php

namespace ONSBKS_Slots\Includes\WooCommerce;
use WC_Product_Simple;

class BookingSlotProduct extends WC_Product_Simple
{

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


}