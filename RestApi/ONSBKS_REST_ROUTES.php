<?php
use ONSBKS_Slots\RestApi\Router;



Router::GET('/info', array('ONSBKS_Slots\RestApi\Controllers\Info', 'check_info'), array('ONSBKS_Slots\RestApi\Middleware', 'Anonymous'));

Router::GET('/products', array('ONSBKS_Slots\RestApi\Controllers\Product', 'get_products'), Router::$AUTH['Anonymous']);
Router::GET('/products/meta', array('ONSBKS_Slots\RestApi\Controllers\Product', 'get_products_meta'), Router::$AUTH['Anonymous']);
Router::POST('/products/meta', array('ONSBKS_Slots\RestApi\Controllers\Product', 'set_products_meta'), Router::$AUTH['Anonymous']);


Router::GET('/options', array('ONSBKS_Slots\RestApi\Controllers\Options', 'get_option'), Router::$AUTH['Anonymous']);
Router::POST('/options', array('ONSBKS_Slots\RestApi\Controllers\Options', 'set_option'), Router::$AUTH['Anonymous']);

Router::GET('/templates', ['\ONSBKS_Slots\RestApi\Controllers\SlotTemplates', 'find_all'], Router::$AUTH['Anonymous']);
Router::POST('/templates', ['\ONSBKS_Slots\RestApi\Controllers\SlotTemplates', 'create'], Router::$AUTH['Anonymous']);
