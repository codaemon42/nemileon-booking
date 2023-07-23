<?php

namespace ONSBKS_Slots\Includes;

use ONSBKS_Slots\Includes\Admin\SlotTemplates;

/**
 * Class Installer
 * @package ONSBKS_Slots\Includes
 * @since 1.0.0
 * Installer handle class
 */
class Installer {

    /**
     * run the installer
     *
     * @since 1.0.0
     */
    public function run() {
        $this->add_version();
        $this->create_page('Booking Slots', do_shortcode("[booking_shortcode]"));
        $this->init_db();
    }

    /**
     * add and update version and last activated time of this plugin
     *
     * @since 1.0.0
     */
    public function add_version() {
        $installed = get_option( 'sbks_installed' );
        if ( !$installed ) {
            update_option( 'sbks_installed', time() );
        }
        update_option( 'onsbks_version', ONSBKS_VERSION );
    }

    /**
     * create page while installing
     *
     * @since 1.0.0
     *
     * @param $title_of_the_page
     * @param $content
     * @param null $parent_id
     */
    public function create_page( $title_of_the_page, $content, $parent_id = NULL ) {
        $page = get_page_by_title( $title_of_the_page, 'OBJECT', 'page' );
        if ( ! empty( $page ) ) {
            return;
        }
        wp_insert_post(
            array(
                'comment_status' => 'close',
                'ping_status'    => 'close',
                'post_author'    => 1,
                'post_title'     => ucwords($title_of_the_page),
                'post_name'      => strtolower(str_replace(' ', '-', trim($title_of_the_page))),
                'post_status'    => 'publish',
                'post_content'   => $content,
                'post_type'      => 'page',
                'post_parent'    =>  $parent_id
            )
        );
    }

    public function init_db(): void
    {
        new SlotTemplates();
    }
}