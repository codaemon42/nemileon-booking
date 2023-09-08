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
      public function pluginPage(): void
      {
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

    /**
     * Loads the required templates for specific address pages
     *
     * @author Naim-Ul-Hassan
     * @since 1.3.1
     *
     * @return void
     */
    public function baseReactPage(): void
    {

        $template = __DIR__ . '/views/address-new.php';

        if ( file_exists( $template ) ) {
            include "$template";
        }
    }
}
