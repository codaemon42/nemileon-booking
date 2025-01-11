<?php

namespace ONSBKS_Slots\includes;
include_once ABSPATH . 'wp-admin/includes/plugin.php';

class Requirements
{

    private string $woocommercePath = 'woocommerce/woocommerce.php';

    public function verify(): bool
    {
        if(!$this->verifyWoocommerceActivation()) return false;

        return true;
    }

    public function verifyWoocommerceActivation(): bool
    {
        if(!is_plugin_active($this->woocommercePath)) {
            deactivate_plugins($this->woocommercePath);
            unset($_GET['activate']);
            add_action('admin_notices', [ $this, 'woocommerceActivationNotice']);
            return false;
        }
        return true;
    }

    public function woocommerceActivationNotice(): void
    {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e('Nemileon Booking For WooCommerce plugin requires WooCommerce to be installed and activated. Please install and activate WooCommerce to use this plugin.', 'text-domain'); ?></p>
        </div>
        <?php
    }

}