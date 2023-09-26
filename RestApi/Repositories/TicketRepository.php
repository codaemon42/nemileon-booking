<?php

namespace ONSBKS_Slots\RestApi\Repositories;

use ONSBKS_Slots\Includes\Constants;
use ONSBKS_Slots\RestApi\Exceptions\TicketNotValid;

class TicketRepository
{
    public $_wpdb;
    public string $table_name = "";
    public string $table_woocommerce_order_items = "";
    public string $table_woocommerce_order_itemmeta = "";
    public string $table_posts = "";

    public function __construct()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;
        $this->table_name = $this->_wpdb->prefix . "nemileon_bookings";
        $this->table_woocommerce_order_items = $this->_wpdb->prefix . "woocommerce_order_items";
        $this->table_woocommerce_order_itemmeta = $this->_wpdb->prefix . "woocommerce_order_itemmeta";
        $this->table_posts = $this->_wpdb->prefix . "posts";

    }

    public  function findOrderByBookingId(int $bookingId): \WC_Order
    {
        $query = $this->_wpdb->prepare("
            SELECT orders.*
            FROM $this->table_woocommerce_order_items AS order_items
            INNER JOIN $this->table_woocommerce_order_itemmeta AS order_item_meta
            ON order_items.order_item_id = order_item_meta.order_item_id
            INNER JOIN $this->table_posts AS orders
            ON order_items.order_id = orders.ID
            WHERE order_item_meta.meta_key = %s
            AND order_item_meta.meta_value = %s
        ", Constants::BOOKING_ID_KEY, $bookingId);

        $order = $this->_wpdb->get_row($query);

        if ($order) {
            return wc_get_order($order->ID);
        } else {
            throw new TicketNotValid();
        }


    }


}