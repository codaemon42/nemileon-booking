<?php

namespace ONSBKS_Slots\Includes;

class WooCommerce
{

    public function __construct()
    {
        new WooCommerce\BookingSlotProduct();
        new WooCommerce\WooInitializer();
    }
}
