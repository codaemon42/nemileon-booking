<?php

namespace ONSBKS_Slots\Includes;

use ONSBKS_Slots\Includes\Admin\SlotTemplates;

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
      function __construct() {
          new Admin\Requirements();
          new SlotTemplates();
          new Admin\Menu();
          $addBookings = new Admin\AddBookings();
            add_action( 'admin_init', [$addBookings, 'add_new_booking'] );
      }

}