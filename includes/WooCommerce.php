<?php

namespace ONSBKS_Slots\Includes;

class WooCommerce
{

    public function __construct()
    {
    //  if(is_plugin_active( 'woocommerce/woocommerce.php' )){
        new WooCommerce\BookingSlotProduct();
        new WooCommerce\WooInitializer();
    //  }

    }
}
