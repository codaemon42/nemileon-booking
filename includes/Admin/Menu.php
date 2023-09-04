<?php

namespace ONSBKS_Slots\Includes\Admin;

/**
 * Class Menu
 * @package ONSBKS_Slots\Includes\Admin
 * admin Menu handler class
 * @since 1.0.0
 */
class Menu {
      /**
       * initialize the admin_menu hook
       *
       * @since 1.0.0
       */
      function __construct() {
            add_action( 'admin_menu', [ $this, 'admin_menu' ] );
      }


    /**
     * initialize the menu
     *
     * @since 1.0.0
     */
      function admin_menu() {
            $parent_slug = 'nml-sports-booking-slot';
            $bookings_slug = 'nml-bookings';
            $slot_templates_slug = 'nml-slot-templates';
            $product_templates_slug = 'nml-product-templates';
            $capability = 'manage_options';

            add_menu_page( 'booking slot page', 'Sports Booking', $capability, $parent_slug, [ $this, 'render_slot_templates_page' ], 'dashicons-buddicons-groups', 71 );
            add_submenu_page( $parent_slug, 'Dashboard', 'Dashboard', $capability, $parent_slug, [ $this, 'render_slot_templates_page' ], 10 );

            add_submenu_page( $parent_slug, 'Add Product Template', 'Add Product Template', $capability, $product_templates_slug, [ $this, 'render_slot_templates_page' ], 10 );

            add_submenu_page( $parent_slug, 'Slot Templates', 'Slot Templates', $capability, $slot_templates_slug, [ $this, 'render_slot_templates_page' ], 15 );

            add_submenu_page( $parent_slug, 'Bookings', 'Bookings', $capability, "$bookings_slug", [ $this, 'render_bookings_page' ], 20 );
      }

      /**
       * initialize the Address Book page
       *
       * @since 1.0.0
       */
      function slotbook_page() {
            wp_enqueue_style('sbks-admin-style');
            wp_enqueue_script('sbks-admin-ajax-script');
            wp_enqueue_script('sbks-frontend-react-script');
            wp_enqueue_style('sbks-frontend-react-style');
            $slotBookPage = new SlotBookPage();
            $slotBookPage->plugin_page();
      }

      /**
       * initialize the add new slot page
       *
       * @since 1.0.0
       */
      function add_new_slot_page() {
            $add_new_page = new SlotBookPage();
            $add_new_page->plugin_page();
      }

      public function render_bookings_page()
      {
          wp_enqueue_script('sbks-frontend-react-script');
          wp_enqueue_style('sbks-frontend-react-style');
          $add_new_page = new SlotBookPage();
          $add_new_page->base_react_page();
      }

    public function render_slot_templates_page()
    {
        wp_enqueue_script('sbks-frontend-react-script');
        wp_enqueue_style('sbks-frontend-react-style');
        $add_new_page = new SlotBookPage();
        $add_new_page->base_react_page();
    }
}
