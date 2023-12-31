<?php

namespace ONSBKS_Slots\Includes;

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
     * @modified
     * @since 1.3.1
     */
    public function run(): void
    {
        $this->addVersion();
        $this->createPageByPath('Booking Slots', 'booking-slot', do_shortcode("[booking_shortcode]"));
        $this->initDb();
    }

    /**
     * add and update version and last activated time of this plugin
     *
     * @since 1.0.0
     */
    public function addVersion(): void
    {
        $installed = get_option( 'sbks_installed' );
        if ( !$installed ) {
            update_option( 'sbks_installed', time() );
        }
        update_option( 'onsbks_version', ONSBKS_VERSION );
    }

    /**
     * @deprecated
     * create page while installing
     *
     * @since 1.0.0
     *
     * @param $title_of_the_page
     * @param $content
     * @param null $parent_id
     */
    public function createPage($title_of_the_page, $content, $parent_id = null ): void
    {
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

    /**
     * create page while installing
     *
     * @param $title_of_the_page
     * @param $slug
     * @param $content
     * @param null $parent_id
     * @since 1.3.1
     *
     */
    public function createPageByPath($title_of_the_page, $slug, $content, $parent_id = null ): void
    {
        $page = get_page_by_path( $slug, 'OBJECT', 'page' );
        if ( ! empty( $page ) ) {
            return;
        }
        wp_insert_post(
            array(
                'comment_status' => 'close',
                'ping_status'    => 'close',
                'post_author'    => 1,
                'post_title'     => ucwords($title_of_the_page),
                'post_name'      => $slug,
                'post_status'    => 'publish',
                'post_content'   => $content,
                'post_type'      => 'page',
                'post_parent'    =>  $parent_id
            )
        );
    }

    public function initDb(): void
    {
        new Entities();
    }
}
