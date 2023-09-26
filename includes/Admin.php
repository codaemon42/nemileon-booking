<?php

namespace ONSBKS_Slots\Includes;

/**
 * Class Admin
 * @package ONSBKS_Slots\Includes
 * handles the admin classes
 * @since 1.0.0
 */
class Admin {

      /**
       * Initialize the admin classes
       * @since 1.0.0
       */
      public function __construct() {
          new Admin\Requirements();
          new Admin\Menu();

          // TODO need to to clean up below code
           $addBookings = new Admin\AddBookings();
           add_action( 'admin_init', [$addBookings, 'add_new_booking'] );
      }

}
