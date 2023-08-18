<?php

namespace ONSBKS_Slots\RestApi\Repositories;

use ONSBKS_Slots\Includes\Models\Slot;

class ProductRepository
{

    /**
     * Prefix of the product meta key
     * @var string
     */
    private string $prefix = "NML_";


    /**
     * Find the value(Slot) from the product meta with the real key (day or date)
     * @since 1.3.1
     * @author Naim Ul Hassan
     *
     * @param int $productId
     * @param string $date
     * @return Slot|null
     */
    public function get_product_slot(int $productId, string $date): ?Slot
    {
        // find with the date
        $key = $this->prefix . $date;
        $value = get_post_meta($productId, $key);

        // if doesn't, find by day
        if(!$value) {
            $shortDay = get_the_date('D', strtotime($date));
            $key = $this->prefix . $shortDay;
            $value = get_post_meta($productId, $key);
        }

        // if doesn't exist return false
        if(!$value) return null;

        return new Slot( $value );
    }


    /**
     * Find the real key whether it is saved as day or date in product meta
     * @since 1.3.1
     * @author Naim Ul Hassan
     *
     * @param int $productId
     * @param string $date
     * @return string|null
     */
    public function get_product_key(int $productId, string $date): ?string
    {
        // find with the date
        $key = $this->prefix . $date;
        $value = get_post_meta($productId, $key);

        // if doesn't, find by day
        if(!$value) {
            $shortDay = get_the_date('D', strtotime($date));
            $key = $this->prefix . $shortDay;
            $value = get_post_meta($productId, $key);
        }

        // if doesn't exist return false
        if(!$value) return null;

        return $key;
    }


    /**
     * Update the product meta with day or date as the key and the slot as the value
     * @since 1.3.1
     * @author Naim-Ul-Hassan
     *
     * @param int $productId
     * @param string $date
     * @param Slot $value
     * @return bool
     */
    public function set_product_slot(int $productId, string $date, Slot $value): bool
    {
        // find key with the date
        $realProductKey = $this->get_product_key($productId, $date);

        // if doesn't exist return false
        if(!$realProductKey) return false;

        return update_post_meta($productId, $realProductKey, $value);
    }


}