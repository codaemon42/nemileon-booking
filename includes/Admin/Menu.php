<?php

namespace ONSBKS_Slots\Includes\Admin;

/**
 * Class Menu
 * @package ONSBKS_Slots\Includes\Admin
 * admin Menu handler class
 * @since 1.0.0
 * @modified 1.3.1
 */
class Menu {
      /**
       * initialize the admin_menu hook
       *
       * @since 1.0.0
       */
      public function __construct() {
            add_action( 'admin_menu', [ $this, 'adminMenu'] );
      }


    /**
     * initialize the menu
     *
     * @author Naim-Ul-Hassan
     * @since 1.0.0
     * @modified 1.3.1
     */
      public function adminMenu(): void
      {
            $parent_slug = 'nml-sports-booking-slot';
            $bookings_slug = 'nml-bookings';
            $settings_slug = 'nml-settings';
            $slot_templates_slug = 'nml-slot-templates';
            $product_templates_slug = 'nml-product-templates';
            $capability = 'manage_options';

            add_menu_page(
                'booking slot page',
                'Sports Booking',
                $capability, $parent_slug,
                [ $this, 'renderReactApp' ],
                'dashicons-buddicons-groups',
                71
            );

            // Analytics Dashboard
            add_submenu_page(
                $parent_slug,
                'Dashboard',
                'Dashboard',
                $capability,
                $parent_slug,
                [ $this, 'renderReactApp' ],
                10
            );

            // Product Template Builder page
            add_submenu_page(
                $parent_slug,
                'Add Product Template',
                'Add Product Template',
                $capability,
                $product_templates_slug,
                [ $this, 'renderReactApp' ],
                10
            );

            // Slot Templates page
            add_submenu_page(
                $parent_slug,
                'Slot Templates',
                'Slot Templates',
                $capability,
                $slot_templates_slug,
                [ $this, 'renderReactApp' ],
                15
            );

            // All Bookings page
            add_submenu_page(
                $parent_slug,
                'Bookings',
                'Bookings',
                $capability,
                $bookings_slug,
                [ $this, 'renderReactApp' ],
                20
            );

            // settings page
            add_submenu_page(
                $parent_slug,
                'Settings',
                'Settings',
                $capability,
                $settings_slug,
                [ $this, 'renderReactApp' ],
                20
            );
      }


    /**
     * Renders the React App for the admin screens
     *
     * @author Naim-Ul-Hassan
     * @since 1.3.1
     *
     * @return void
     */
      public function renderReactApp(): void
      {
          wp_enqueue_script('sbks-frontend-react-script');
          wp_enqueue_style('sbks-frontend-react-style');
          $add_new_page = new SlotBookPage();
          $add_new_page->baseReactPage();
      }
}
