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
            $parent_slug = 'sports-booking-slot';
            $capability = 'manage_options';

            add_menu_page( 'booking slot page', 'Sports BookingsEntity', $capability, $parent_slug, [ $this, 'slotbook_page' ], 'dashicons-buddicons-groups', 71 );
            add_submenu_page( $parent_slug, 'BookingsEntity Slot Lists', 'BookingsEntity Slot Lists', $capability, $parent_slug, [ $this, 'slotbook_page' ], 10 );
            add_submenu_page( $parent_slug, 'Add New Slot', 'Add New Slot', $capability, "{$parent_slug}&action=new", [ $this, 'add_new_slot_page' ], 10 );
            add_submenu_page( $parent_slug, 'Slot Templates', 'Slot Templates', $capability, "{$parent_slug}&action=templates", [ $this, 'add_new_slot_page' ], 15 );
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
}
