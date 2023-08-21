<?php
/**
 * Plugin Name:   Sports Bookings Slot
 * Description:   This plugin allow users to book different available slots of sports venues.
 * Plugin URI:    https://woopearl.com/
 * Author:        Naim-Ul-Hassan
 * Author URI:    https://github.com/codaemon42
 * Version:       1.3.1
 * License:       GPLv2 or later
 * License URI:   https://gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
      exit;
}

require_once( __DIR__ . '/vendor/autoload.php' );

/**
 * Class ONSBKS_Slots
 * The Main plugin class
 * @since 1.0.0
 */
final class ONSBKS_Slots {

      /**
       * version string describes the version of the plugin
       *
       * @since 1.0.0
       */
      const version = '1.3.1';

      /**
       * construct the class
       *
       * @since 1.0.0
       */
      private function __construct() {
            $this->define_constants();

            register_activation_hook( ONSBKS_FILE, [ $this, 'activate' ] );

            add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
      }

    /**
     * initialize the plugin once through life cycle
     *
     * @since 1.0.0
     *
     * @return ONSBKS_Slots
     */

      public static function init(): ONSBKS_Slots {
            static $instance = false;
            if ( !$instance ) {
                  $instance = new self();
            }
            return $instance;
      }

      /**
       * initialize the plugin and its classes and php scripts
       *
       * @since 1.0.0
       *
       * @return void
       */
      public function init_plugin() {
          new ONSBKS_Slots\Includes\Orders();
          new ONSBKS_Slots\Includes\Assets();
          new ONSBKS_Slots\Includes\Ajax();

          new ONSBKS_Slots\Includes\WooCommerce();
          if( is_admin() ) {
                  new ONSBKS_Slots\Includes\Admin();
            } else {
                  new ONSBKS_Slots\Includes\Frontend();
            }
      }

      /**
       * define plugin's constants
       *
       * @since 1.0.0
       * 
       * @return void
       */
      public function define_constants() {
            define( 'ONSBKS_VERSION', self::version );
            define( 'ONSBKS_FILE', __FILE__ );
            define( 'ONSBKS_DIR', __DIR__ );
            define( 'ONSBKS_URL', plugins_url( '', ONSBKS_FILE ));
            define( 'ONSBKS_ASSETS', ONSBKS_URL.'/assets' );
            define( 'ONSBKS_REACT_BUILD', ONSBKS_URL.'/build' );
            define( 'ONSBKS_JWT_KEY', 'your_secret_key' );
      }

      /**
       * do stuff on plugin activation
       *
       * @since 1.0.0
       *
       * @return void
       */
      public function activate() {
            $installer = new ONSBKS_Slots\Includes\Installer();
            $installer->run();
      }

}


/**
 * Initializes the main plugin
 *
 * @since 1.0.0
 *
 * @return ONSBKS_Slots
 */
function onsbks_slots(): ONSBKS_Slots {
      return ONSBKS_Slots::init();
}

/**
 * call the main plugin function
 */

onsbks_slots();
