<?php

namespace ONSBKS_Slots\Includes\Admin;

/**
 * Class SlotList
 * @package ONSBKS_Slots\Includes\Admin
 */
class SlotList {

    /**
     * contains limit to db query
     *
     * @since 1.0.0
     *
     * @var int
     */
    public $limit;

    /**
     * contains offset value
     *
     * @since 1.0.0
     *
     * @var int
     */
    public $offset;

    /**
     * contains present page number
     *
     * @since 1.0.0
     *
     * @var int
     */
    public $pagenum;

    /**
     * contains main query prefix
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $query_prefix;

    /**
     * contains count query prefix
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $query_prefix_counting;

    /**
     * contains main query suffix
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $query_suffix;

    /**
     * sets basic variables
     *
     * @since 1.0.0
     *
     * @return void
     */
    function set_basic_vars() {
        $this->limit = 18;
        $this->pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
        $this->offset = ( $this->pagenum - 1 ) * $this->limit;
        $this->query_prefix = "SELECT posts.ID, posts.post_title, postmeta.meta_key";
        $this->query_prefix_counting = "SELECT COUNT(ID)";
        $this->query_suffix = " LIMIT {$this->offset}, {$this->limit}";
    }

    /**
     * get base mysql base_query statement without prefix and suffix
     *
     * @since 1.0.0
     *
     * @return string base_query
     */
    function get_base_query(): string {
        global $wpdb;
        if ( isset ( $_POST['sbks_search_date_submit'] ) ) {
            $base_query =  " FROM {$wpdb->prefix}posts AS posts INNER JOIN {$wpdb->prefix}postmeta AS postmeta ON posts.ID = postmeta.post_id AND postmeta.meta_key = 'sbks_product_date{$_POST['sbks_search_date']}' ORDER BY postmeta.meta_key DESC";
        }else {
            $base_query = " FROM {$wpdb->prefix}posts AS posts INNER JOIN {$wpdb->prefix}postmeta AS postmeta ON posts.ID = postmeta.post_id AND postmeta.meta_key LIKE '%sbks_product_date%' ORDER BY postmeta.meta_key DESC";
        }
        return $base_query;
    }

    /**
     * get count mysql query statement
     *
     * @since 1.0.0
     *
     * @return string
     */
    function get_count_query(): string {
        $this->set_basic_vars();
        $base_query = $this->get_base_query();
        return $count_query = $this->query_prefix_counting . $base_query;
    }

    /**
     * get slots mysql query statement
     *
     * @since 1.0.0
     *
     * @return string
     */
    function get_trips_query(): string {
        $this->set_basic_vars();
        $base_query = $this->get_base_query();

        return $main_query = $this->query_prefix . $base_query;
    }

    /**
     * contains total page number
     *
     * @since 1.0.0
     *
     * @return int
     */
    function get_total_page(): int {
        global $wpdb;
        $this->set_basic_vars();
        $base_query = $this->get_base_query();
        $main_query = $this->query_prefix_counting . $base_query; // total count needs no suffix for limit
        $total = $wpdb->get_results( $main_query, 'ARRAY_A' );
        $total_trips =  $total[0]['COUNT(ID)'];
        return $num_of_pages = ceil( $total_trips / $this->limit );
    }

    /**
     * get pagination link
     *
     * @since 1.0.0
     *
     * @return string|array|void
     */
    function get_pagination() {
        $this->set_basic_vars();
        $num_of_pages = $this->get_total_page();
        return $page_links = paginate_links(
            array(
                'base' => add_query_arg( 'pagenum', '%#%' ),
                'format' => '',
                'prev_text' => __( 'PREVIOUS', 'text-domain' ),
                'next_text' => __( 'NEXT', 'text-domain' ),
                'total' => $num_of_pages,
                'current' => $this->pagenum
            )
        );
    }

    /**
     * fetch product id, title and meta_key which stores slots per page
     *
     * @since 1.0.0
     *
     * @return array|null
     */
    function get_id_title_key(): ?array {
        global $wpdb;
        $this->set_basic_vars();
        $base_query = $this->get_base_query();
        $main_query = $this->query_prefix . $base_query . $this->query_suffix;

        return $trips = $wpdb->get_results( $main_query, 'ARRAY_A' );
    }
}
