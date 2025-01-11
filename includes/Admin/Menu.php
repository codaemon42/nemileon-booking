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

    const MAIN_PAGE_TITLE = 'Nemileon Booking';
    const DASHBOARD_PAGE = 'Dashboard';
    const PRODUCT_TEMPLATE_BUILDER_PAGE = 'Add Product Template';
    const BOOKING_TEMPLATE_PAGE = 'Booking Templates';
    const BOOKINGS_PAGE = 'Bookings';
    const SETTINGS_PAGE = 'Settings';

    const MAIN_SLUG = 'nml-sports-booking-slot';
    const BOOKINGS_SLUG = 'nml-bookings';
    const SETTINGS_SLUG = 'nml-settings';
    const BOOKING_TEMPLATE_SLUG = 'nml-slot-templates';
    const PRODUCT_TEMPLATE_BUILDER_SLUG = 'nml-product-templates';
    const MENU_CAPABILITY = 'manage_options';

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
            add_menu_page(
                self::MAIN_PAGE_TITLE,
                self::MAIN_PAGE_TITLE,
                self::MENU_CAPABILITY,
                self::MAIN_SLUG,
                [ $this, 'renderReactApp' ],
                ONSBKS_ASSETS . "/nemileon-icon-dark-bg.png",
                58
            );

            $menuItems = $this->getMenuItems();
            foreach ($menuItems as $menuItem ) {
                add_submenu_page(
                    self::MAIN_SLUG,
                    $menuItem['page'],
                    $menuItem['page'],
                    self::MENU_CAPABILITY,
                    $menuItem['slug'],
                    [ $this, 'renderReactApp' ]
                );
            }
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

    /**
     * List of menu items to show in admin side-menu
     *
     * @author Naim-Ul-Hassan
     * @since 1.3.1
     *
     * @return array[]
     */
      public function getMenuItems(): array
      {
          return [
              [
                  'page' => self::DASHBOARD_PAGE,
                  'slug' => self::MAIN_SLUG
              ],
              [
                  'page' => self::PRODUCT_TEMPLATE_BUILDER_PAGE,
                  'slug' => self::PRODUCT_TEMPLATE_BUILDER_SLUG
              ],
              [
                  'page' => self::BOOKING_TEMPLATE_PAGE,
                  'slug' => self::BOOKING_TEMPLATE_SLUG
              ],
              [
                  'page' => self::BOOKINGS_PAGE,
                  'slug' => self::BOOKINGS_SLUG
              ],
              [
                  'page' => self::SETTINGS_PAGE,
                  'slug' => self::SETTINGS_SLUG
              ]
          ];
      }
}
