<?php

namespace ONSBKS_Slots\Includes\Frontend;

use ONSBKS_Slots\Includes\WooCommerce\BookingSlotProduct;
//use WC_Product_Query;

/**
 * Class Shortcode
 * @package ONSBKS_Slots\Includes\Frontend
 */
class Shortcode
{

      /**
       * Initializes shortcodes
       *
       * @since 1.0.0
       */
      function __construct() {
//            add_shortcode( 'booking_shortcode', [ $this, 'render_shortcode' ] );
            add_shortcode( 'booking_shortcode', [ $this, 'render_react_shortcode' ] );
      }


    /**
     * render shortcodes
     *
     * @since 1.0.0
     *
     * @param $attr
     * @param string $content
     *
     * @return false|string
     */
      public function render_shortcode( $attr, $content = '' ) {
//            global $wpdb;
//            $products = $wpdb->get_results("SELECT {$wpdb->prefix}posts.ID, {$wpdb->prefix}posts.post_title, {$wpdb->prefix}postmeta.meta_value FROM {$wpdb->prefix}posts INNER JOIN {$wpdb->prefix}postmeta ON {$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id AND {$wpdb->prefix}posts.post_type = 'product' AND {$wpdb->prefix}posts.post_status = 'publish' AND {$wpdb->prefix}postmeta.meta_key = '_price'", 'ARRAY_A' );
          $products = onsbks_get_products();
//          $args = array(
//              'post_type'      => 'product',
//              'type'   => 'booking_slot',
//              'posts_per_page' => -1,
//          );
//
//          $products_query = new WC_Product_Query($args);
//          $products = $products_query->get_products();

          foreach ($products as $product) {
//              $product = new BookingSlotProduct($product);
              echo "   .   id:: ". $product->get_id() .", title:: ". $product->get_title() . ", type:: ". $product->get_type();

          }

            wp_enqueue_style('sbks-frontend-style');
            wp_enqueue_script('sbks-frontend-script');
              wp_enqueue_style('sbks-frontend-react-style');
              wp_enqueue_script('sbks-frontend-react-script');
            $post_metas = [];
            foreach ($products as $product) {
                    $product = new BookingSlotProduct($product);
                  $single_meta = $this->render_post_meta( $product->get_id() );
                  array_push( $post_metas, $single_meta );
            }
            $times = $this->get_times();
            ob_start();
                include( __DIR__ . '/views/newbook.php');
            return ob_get_clean();
      }

    /**
     * render shortcodes with react
     *
     * @since 1.3.1
     *
     * @param $attr
     * @param string $content
     *
     * @return false|string
     */
    public function render_react_shortcode( $attr, $content = '' ) {
        wp_enqueue_style('sbks-frontend-react-style');
        wp_enqueue_script('sbks-frontend-react-script');

        ob_start();
        include( __DIR__ . '/views/booking-with-react.php');
        return ob_get_clean();
    }

    /**
     * renders all 3 days time wise slot vacant
     *
     * @since 1.0.0
     *
     * @param $product_id
     *
     * @return array
     */
      public function render_post_meta($product_id): array {
            $nulled_time = $this->get_nulled_times();
            $date_1 = $this->get_date('+0 day');
            $date_2 = $this->get_date('+1 day');
            $date_3 = $this->get_date('+2 day');

            $meta_1 = get_post_meta( $product_id, "sbks_product_date{$date_1}", true );
            $meta_2 = get_post_meta( $product_id, "sbks_product_date{$date_2}", true );
            $meta_3 = get_post_meta( $product_id, "sbks_product_date{$date_3}", true );

          return $all_meta_array = array(
                  "sbks_product_date{$date_1}" => $meta_1 ? $meta_1 : $nulled_time,
                  "sbks_product_date{$date_2}" => $meta_2 ? $meta_2 : $nulled_time,
                  "sbks_product_date{$date_3}" => $meta_3 ? $meta_3 : $nulled_time
          );
      }

    /**
     * retrieves dates from today
     *
     * @since 1.0.0
     *
     * @param $day
     *
     * @return false|string
     */
      function get_date($day) {
            if ( isset( $_POST['sbks_select_date_submit'] ) ) {
                  $this_date = sanitize_text_field( $_POST['sbks_select_date'] );
                  return $date = date("Y-m-d", strtotime($this_date.' '.$day));
            }
            return $date = date("Y-m-d", strtotime($day));
      }

      /**
       * stores the time array
       *
       * @since 1.0.0
       *
       * @return array
       */
      function get_times(): array {
            return $time_hours = array(
                  '12:00_am',
                  '01:00_am',
                  '02:00_am',
                  '03:00_am',
                  '04:00_am',
                  '05:00_am',
                  '06:00_am',
                  '07:00_am',
                  '08:00_am',
                  '09:00_am',
                  '10:00_am',
                  '11:00_am',
                  '12:00_pm',
                  '01:00_pm',
                  '02:00_pm',
                  '03:00_pm',
                  '04:00_pm',
                  '05:00_pm',
                  '06:00_pm',
                  '07:00_pm',
                  '08:00_pm',
                  '09:00_pm',
                  '10:00_pm',
                  '11:00_pm'
            );
      }

      /**
       * stores the time slot assoc array
       *
       * @since 1.0.0
       *
       * @return array
       */
      function get_nulled_times(): array {
            return $time_hours = array(
                  '12:00_am' => 0,
                  '01:00_am' => 0,
                  '02:00_am' => 0,
                  '03:00_am' => 0,
                  '04:00_am' => 0,
                  '05:00_am' => 0,
                  '06:00_am' => 0,
                  '07:00_am' => 0,
                  '08:00_am' => 0,
                  '09:00_am' => 0,
                  '10:00_am' => 0,
                  '11:00_am' => 0,
                  '12:00_pm' => 0,
                  '01:00_pm' => 0,
                  '02:00_pm' => 0,
                  '03:00_pm' => 0,
                  '04:00_pm' => 0,
                  '05:00_pm' => 0,
                  '06:00_pm' => 0,
                  '07:00_pm' => 0,
                  '08:00_pm' => 0,
                  '09:00_pm' => 0,
                  '10:00_pm' => 0,
                  '11:00_pm' => 0
            );
      }
}
