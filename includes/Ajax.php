<?php

namespace ONSBKS_Slots\Includes;

/**
 * Class Ajax
 * @package ONSBKS_Slots\Includes
 * Plugins Ajax handler class
 * @since 1.0.0
 */
class Ajax {

    /**
     * initialize the Ajax
     * @since 1.0.0
     */
    function __construct() {
        $this->initAjax();
    }

    /**
     * enqueue the admin and frontend ajax
     * @since 1.0.0
     */
    public function initAjax() {
        new Frontend\Ajax();
        new Admin\Ajax();
    }
}