<?php

namespace ONSBKS_Slots\Includes\Admin;


/**
 * Class SlotBookPage
 * @package ONSBKS_Slots\Includes\Admin
 * SlotBook page handler class
 * @since 1.0.0
 */
class SlotBookPage {

      /**
       * Loads the required templates for specific address pages
       *
       * @since 1.0.0
       */
      public function plugin_page() {
            $action = isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : 'list';

            switch ( $action ) {
                  case 'new':
                        $template = __DIR__ . '/views/address-new.php';
                        break;

                case 'templates':
                    $template = __DIR__ . '/views/slot-templates.php';
                    break;

                default:
                    $template = __DIR__ . '/views/address-list.php';
                    break;
            }

            if ( file_exists( $template ) ) {
                  include "$template";
            }
      }

    public function base_react_page() {

        $template = __DIR__ . '/views/address-new.php';

        if ( file_exists( $template ) ) {
            include "$template";
        }
    }

    /**
     * fetch all product ids and titles
     *
     * @since 1.0.0
     *
     * @return array|object|null
     */
      static function get_product_id_title() {
            global $wpdb;

          $args = array(
              'post_type'      => 'product',
              'product_type'   => 'booking_slot',
              'posts_per_page' => -1,
          );

//          $query = new \WC_Product_Query($args);
          $products = wc_get_products($args);
          return $products;
          //  return  $wpdb->get_results("SELECT ID, post_title FROM {$wpdb->prefix}posts WHERE post_type = 'product' AND post_status = 'publish'", 'ARRAY_A');
      }


    /**
     * fetch all product ids and titles
     *
     * @since 1.0.0
     *
     * @return array|object|null
     */
    static function get_products() {
        $args = array(
            'post_type'      => 'product',
            'product_type'   => 'booking_slot',
            'posts_per_page' => -1,
        );

        return wc_get_products($args);

   }
}
