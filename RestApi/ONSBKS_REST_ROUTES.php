<?php
use ONSBKS_Slots\RestApi\Router;

use ONSBKS_Slots\RestApi\Controllers\BookingController;
use ONSBKS_Slots\RestApi\Controllers\SlotTemplates;
use ONSBKS_Slots\RestApi\Controllers\Options;
use ONSBKS_Slots\RestApi\Controllers\ProductController;
use ONSBKS_Slots\RestApi\Controllers\Info;

$ROUTER = new Router();

$infoController = new Info();
$ROUTER->GET('/info', array($infoController, 'check_info'), array('ONSBKS_Slots\RestApi\Middleware', 'Anonymous'));

$productController = new ProductController();
$ROUTER->GET('/products', array($productController, 'get_products'), $ROUTER->AUTH['Anonymous']);
$ROUTER->GET('/products/meta', array($productController, 'get_products_meta'), $ROUTER->AUTH['Anonymous']);
$ROUTER->POST('/products/template', array($productController, 'set_booking_template'), $ROUTER->AUTH['Anonymous']);
$ROUTER->GET('/products/templates', array($productController, 'get_booking_templates'), $ROUTER->AUTH['Anonymous']);

$optionsController = new Options();
$ROUTER->GET('/options', array($optionsController, 'get_option'), $ROUTER->AUTH['Anonymous']);
$ROUTER->POST('/options', array($optionsController, 'set_option'), $ROUTER->AUTH['Anonymous']);

$slotTemplateController = new SlotTemplates();
$ROUTER->GET('/templates', [$slotTemplateController, 'find_all'], $ROUTER->AUTH['Anonymous']);
$ROUTER->POST('/templates', [$slotTemplateController, 'create'], $ROUTER->AUTH['Anonymous']);
$ROUTER->PUT('/templates', [$slotTemplateController, 'update'], $ROUTER->AUTH['Anonymous']);
$ROUTER->DELETE('/templates', [$slotTemplateController, 'delete'], $ROUTER->AUTH['Anonymous']);

$bookingController = new BookingController();
$ROUTER->GET('/bookings', [$bookingController, 'createBooking'] ,$ROUTER->AUTH['Test']);
