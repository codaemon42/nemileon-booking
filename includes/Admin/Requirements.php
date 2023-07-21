<?php

namespace ONSBKS_Slots\Includes\Admin;

/**
 * Class Requirements
 * @package ONSBKS_Slots\Includes\Admin
 * @since 1.0.0
 * handles all admin screen requirements for the plugin
 */
class Requirements {

    /**
     * Requirements constructor.
     *
     * draw admin notices for every requirements
     *
     * @since 1.0.0
     */
    public function __construct() {
        $this->inactive_plugin_checker();
    }

    /**
     * checks for inactive required plugins
     *
     * @since 1.0.0
     */
    function inactive_plugin_checker() {
        if ( ! function_exists( 'is_plugin_active' ) ) {
            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
        }
        if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            add_action( 'admin_notices', [ $this, 'sbks_woocommerce_notice' ] );
        }
    }

    /**
     * Admin Error notice for inactive woocommerce plugin
     *
     * @since 1.0.0
     */
    public function sbks_woocommerce_notice() {
        $class = 'notice notice-error';
        $message = 'Sports booking slot plugin requires woocommerce plugin to work successfully...';

        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
    }
}