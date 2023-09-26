<?php

namespace ONSBKS_Slots\Includes;

/**
 * Class Frontend
 * @package ONSBKS_Slots\Includes
 * handles frontend classes
 * @since 1.0.0
 */
class Frontend {
      /**
       * Initialize the frontend classes
       *
       * @since 1.0.0
       */
      public function __construct() {
            new Frontend\Shortcode();
      }
}

