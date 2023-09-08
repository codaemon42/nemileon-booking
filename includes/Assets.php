<?php

namespace ONSBKS_Slots\Includes;

/**
 * Class Assets
 * @package ONSBKS_Slots\Includes
 * plugins Assets handler class
 * @since 1.0.0
 */
class Assets {

    /**
     * enqueue scripts
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'registerScript'] );
        add_action( 'admin_enqueue_scripts', [ $this, 'registerScript'] );
    }

    /**
     * Prepare script to be registered
     *
     * @since 1.0.0
     * @return array
     */
    public function prepareScript(): array
    {
        return array(
                'sbks-admin-ajax-script' => array(
                    'src'   => ONSBKS_ASSETS . '/js/admin-ajax-script.js',
                    'ver'   => ONSBKS_DIR . '/assets/js/admin-ajax-script.js',
                    'deps'  => array('jquery')
                ),
                'sbks-frontend-react-script' => array(
                    'src'   => ONSBKS_REACT_BUILD . '/index.js',
                    'ver'   => ONSBKS_DIR . '/build/index.js',
                    'deps'  => array('wp-element')
                )
        );
    }

    /**
     * Prepare styles to be registered
     *
     * @since 1.0.0
     * @return array
     */
    public function prepareStyle(): array
    {
        return array(
            'sbks-frontend-style' => array(
                'src'   => ONSBKS_ASSETS . '/css/frontend.css',
                'ver'   => ONSBKS_DIR . '/assets/css/frontend.css',
                'deps'  => array()
            ),
            'sbks-admin-style' => array(
                'src'   => ONSBKS_ASSETS . '/css/admin.css',
                'ver'   => ONSBKS_DIR . '/assets/css/admin.css',
                'deps'  => array()
            ),
            'sbks-frontend-react-style' => array(
                'src'   => ONSBKS_REACT_BUILD . '/index.css',
                'ver'   => ONSBKS_DIR . '/build/index.css',
                'deps'  => array()
            )
        );
    }

    /**
     * Register all Scripts
     *
     * @since 1.0.0
     * @return void
     */
    public function registerScript(): void
    {
        $scripts = $this->prepareScript();
        foreach ( $scripts as $handler => $script ) {
            wp_register_script( $handler, $script['src'], $script['deps'], filemtime($script['ver']), true );
        }

        $styles = $this->prepareStyle();
        foreach ( $styles as $handler => $style ) {
            wp_register_style( $handler, $style['src'], $style['deps'], filemtime($style['ver']) );
        }

        wp_localize_script( 'sbks-frontend-react-script', 'reactObj', array(
            'base_url'  => site_url(),
            'ajax_url'  => admin_url( 'admin-ajax.php' ),
            'nonce'     => wp_create_nonce( 'onsbks_react_nonce' ),
            'jwt'       => onsbks_create_jwt(),
            'select'    => 'successfully selected...',
            'approve'   => 'successfully approved...',
            'error'     => 'something went wrong, request denied...',
            'is_admin'  => is_admin(),
            'order_status'=> wc_get_order_statuses(),
            'page'      => is_admin() ? $_GET["page"] ?? $_SERVER['REQUEST_URI'] : $_SERVER['REQUEST_URI'],
            'action'    => is_admin() ? $_GET["action"] ?? $_SERVER['REQUEST_URI'] : $_SERVER['REQUEST_URI'],
        ) );
    }
}
