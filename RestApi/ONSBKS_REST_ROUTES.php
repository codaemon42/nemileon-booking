<?php

use ONSBKS_Slots\RestApi\Controllers\AnalyticsController;
use ONSBKS_Slots\RestApi\Controllers\TicketController;
use ONSBKS_Slots\RestApi\Router;

use ONSBKS_Slots\RestApi\Controllers\BookingController;
use ONSBKS_Slots\RestApi\Controllers\SlotTemplates;
use ONSBKS_Slots\RestApi\Controllers\OptionsController;
use ONSBKS_Slots\RestApi\Controllers\ProductController;
use ONSBKS_Slots\RestApi\Controllers\Info;
use ONSBKS_Slots\RestApi\Repositories\AnalyticsRepository;
use ONSBKS_Slots\RestApi\Services\AnalyticsService;
use ONSBKS_Slots\RestApi\Repositories;

$repo = new Repositories();

 $ROUTER = new Router();
 $ROUTER->set_auth('Test', '__return_true');

 $infoController = new Info();
 $ROUTER->GET('/info', array($infoController, 'check_info'), array('ONSBKS_Slots\RestApi\Middleware', 'Anonymous'));

 $productController = new ProductController();
 $ROUTER->GET('/products', array($productController, 'get_products'), $ROUTER->AUTH['Anonymous']);
 $ROUTER->GET('/products/meta', array($productController, 'get_products_meta'), $ROUTER->AUTH['Anonymous']);
 $ROUTER->POST('/products/template', array($productController, 'set_booking_template'), $ROUTER->AUTH['Anonymous']);
 $ROUTER->GET('/products/templates', array($productController, 'get_booking_templates'), $ROUTER->AUTH['Anonymous']);

 $optionsController = new OptionsController();
 $ROUTER->GET('/options', array($optionsController, 'getOption'), $ROUTER->AUTH['Admin']);
 $ROUTER->POST('/options', array($optionsController, 'setOption'), $ROUTER->AUTH['Admin']);
 $ROUTER->GET('/settings', array($optionsController, 'findSettings'), $ROUTER->AUTH['Admin']);
 $ROUTER->POST('/settings', array($optionsController, 'saveSettings'), $ROUTER->AUTH['Admin']);

 $slotTemplateController = new SlotTemplates();
 $ROUTER->GET('/templates', [$slotTemplateController, 'find_all'], $ROUTER->AUTH['Anonymous']);
 $ROUTER->POST('/templates', [$slotTemplateController, 'create'], $ROUTER->AUTH['Anonymous']);
 $ROUTER->PUT('/templates', [$slotTemplateController, 'update'], $ROUTER->AUTH['Anonymous']);
 $ROUTER->DELETE('/templates', [$slotTemplateController, 'delete'], $ROUTER->AUTH['Anonymous']);

 $bookingController = new BookingController();
 $ROUTER->GET('/bookings', [$bookingController, 'findAllBookings'] ,$ROUTER->AUTH['Test']);
 $ROUTER->GET('/bookings/users/(?P<id>\d+)', [$bookingController, 'findAllBookingsByUserIdOrFingerPrint'] ,$ROUTER->AUTH['Anonymous']);
 $ROUTER->GET('/bookings/users/count', [$bookingController, 'countAllBookingsByUserIdOrFingerPrint'] ,$ROUTER->AUTH['User']);
 $ROUTER->DELETE('/bookings/(?P<id>\d+)', [$bookingController, 'cancelBookingByBookingId'] ,$ROUTER->AUTH['User']);
 $ROUTER->POST('/bookings', [$bookingController, 'createBooking'] ,$ROUTER->AUTH['Anonymous']);
 $ROUTER->PUT('/bookings/(?P<id>\d+)', [$bookingController, 'createBooking'] ,$ROUTER->AUTH['Test']);
 $ROUTER->GET('/bookings/(?P<id>\d+)', [$bookingController, 'findBookingByBookingId'] ,$ROUTER->AUTH['Test']);

 $ticketController = new TicketController();
$ROUTER->GET('/tickets/find/(?P<id>\d+)', [$ticketController, 'findTicket'] ,$ROUTER->AUTH['User']);
$ROUTER->GET('/tickets/verify/(?P<id>\d+)', [$ticketController, 'verifyTicket'] ,$ROUTER->AUTH['Anonymous']);

$analyticsRepository = new AnalyticsRepository();
 $analyticsService = new AnalyticsService($analyticsRepository);
 $analyticsController = new AnalyticsController($analyticsService);
 $ROUTER->GET('/analytics', [$analyticsController, 'findBookingAnalyticsByDate'] ,$ROUTER->AUTH['Test']);
 $ROUTER->GET('/analytics-status', [$analyticsController, 'findBookingAnalyticsByDateAndStatus'] ,$ROUTER->AUTH['Test']);
