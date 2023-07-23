<?php

namespace ONSBKS_Slots\Includes\WooCommerce;

use ONSBKS_Slots\Includes\WooCommerce\BookingSlotProduct;

class WooInitializer
{

    public function __construct()
    {
        add_filter( 'product_type_selector', [$this, 'add_booking_slot_product_type'] );
        add_filter( 'woocommerce_product_class', [$this, 'register_booking_slot_product_type'], 10, 2 );
        add_filter( 'woocommerce_product_data_tabs', [$this, 'booking_slot_product_tabs'] );
        add_action( 'woocommerce_product_data_panels', [$this, 'booking_slot_product_tab_content'] );
        add_action('woocommerce_process_product_meta_booking_slot', [$this, 'wc_save_booking_slot_custom_fields']);
    }

    /**
     * Register BookingSlot product type class
     * @param $classname
     * @param $product_type
     * @return mixed|string
     */
    public function register_booking_slot_product_type($classname, $product_type)
    {
        if ($product_type === 'booking_slot') {
            $classname = 'ONSBKS_Slots\Includes\WooCommerce\BookingSlotProduct';
        }

        return $classname;
    }

    /**
     * Add BookingSlot Product type to the Selector
     * @param $types
     * @return mixed
     */
    public function add_booking_slot_product_type( $types ){
        $types['booking_slot'] = 'Booking Slot Product';
        return $types;
    }


    /**
     * Add a Booking Slot tab for BookingSlot products.
     */
    public function booking_slot_product_tabs( $tabs )
    {

        $tabs['booking_slot'] = array(
            'label'		=> __( 'Booking Slot', 'woocommerce' ),
            'target'	=> 'booking_slot_options',
            'class'		=> array( 'show_if_booking_slot' ),
            'priority'  => 5
        );

        return $tabs;

    }


    /**
     * Contents of the booking_slot tab.
     */
    public function booking_slot_product_tab_content()
    {

        global $post;
        $product_object = $post->ID ? wc_get_product( $post->ID ) : new BookingSlotProduct();

        ?><div id='booking_slot_options' class='panel woocommerce_options_panel'><?php

        ?><div class='options_group'>
        <?php

        woocommerce_wp_text_input(
            array(
                'id'        => 'bk_regular_price',
                'value'     => $product_object->get_regular_price( 'edit' ),
                'label'     => __( 'Regular price', 'woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
                'data_type' => 'price',
            )
        );

        woocommerce_wp_text_input(
            array(
                'id'          => 'bk_sale_price',
                'value'       => $product_object->get_sale_price( 'edit' ),
                'data_type'   => 'price',
                'label'       => __( 'Sale price', 'woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
                'description' => '<a href="#" class="sale_schedule">' . __( 'Schedule', 'woocommerce' ) . '</a>',
            )
        );

        $sale_price_dates_from_timestamp = $product_object->get_date_on_sale_from( 'edit' ) ? $product_object->get_date_on_sale_from( 'edit' )->getOffsetTimestamp() : false;
        $sale_price_dates_to_timestamp   = $product_object->get_date_on_sale_to( 'edit' ) ? $product_object->get_date_on_sale_to( 'edit' )->getOffsetTimestamp() : false;

        $sale_price_dates_from = $sale_price_dates_from_timestamp ? date_i18n( 'Y-m-d', $sale_price_dates_from_timestamp ) : '';
        $sale_price_dates_to   = $sale_price_dates_to_timestamp ? date_i18n( 'Y-m-d', $sale_price_dates_to_timestamp ) : '';

        echo '<p class="form-field sale_price_dates_fields">
				<label for="_sale_price_dates_from">' . esc_html__( 'Sale price dates', 'woocommerce' ) . '</label>
				<input type="text" class="short" name="bk_sale_price_dates_from" id="bk_sale_price_dates_from" value="' . esc_attr( $sale_price_dates_from ) . '" placeholder="' . esc_html( _x( 'From&hellip;', 'placeholder', 'woocommerce' ) ) . ' YYYY-MM-DD" maxlength="10" pattern="' . esc_attr( apply_filters( 'woocommerce_date_input_html_pattern', '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' ) ) . '" />
				<input type="text" class="short" name="bk_sale_price_dates_to" id="bk_sale_price_dates_to" value="' . esc_attr( $sale_price_dates_to ) . '" placeholder="' . esc_html( _x( 'To&hellip;', 'placeholder', 'woocommerce' ) ) . '  YYYY-MM-DD" maxlength="10" pattern="' . esc_attr( apply_filters( 'woocommerce_date_input_html_pattern', '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' ) ) . '" />
				<a href="#" class="description cancel_sale_schedule">' . esc_html__( 'Cancel', 'woocommerce' ) . '</a>' . wc_help_tip( __( 'The sale will start at 00:00:00 of "From" date and end at 23:59:59 of "To" date.', 'woocommerce' ) ) . '
			</p>';

        ?>
        </div>

        </div><?php
    }

    // Save custom fields when the product is saved.
    function wc_save_booking_slot_custom_fields($post_id)
    {
        $product = wc_get_product($post_id);

        if ('booking_slot' === $product->get_type()) {
            $this->save_custom_fields($post_id);
        }
    }

    /**
     * Save custom fields data when the product is saved.
     *
     * @param int $post_id
     */
    public function save_custom_fields($post_id)
    {
        $regular_price = isset($_POST['bk_regular_price']) ? wc_format_decimal($_POST['bk_regular_price']) : '';
        $sale_price = isset($_POST['bk_sale_price']) ? wc_format_decimal($_POST['bk_sale_price']) : '';

        $price = $sale_price != '' ? $sale_price : $regular_price;

        $sale_price_dates_from = isset($_POST['bk_sale_price_dates_from']) ? $_POST['bk_sale_price_dates_from'] : false;

        $sale_price_dates_to = isset($_POST['bk_sale_price_dates_to']) ? $_POST['bk_sale_price_dates_to'] : false;


        update_post_meta($post_id, '_regular_price', $regular_price);
        update_post_meta($post_id, '_sale_price', $sale_price);
        update_post_meta($post_id, '_price', $price);

        update_post_meta($post_id, '_sale_price_dates_from', $sale_price_dates_from);
        update_post_meta($post_id, '_sale_price_dates_to', $sale_price_dates_to);
    }

}