<?php
namespace ONSBKS_Slots\RestApi;

class Router
{
    public static $base_namespace = "onsbks/v2";

    public static $AUTH = [
        'Anonymous' => array('ONSBKS_Slots\RestApi\Middleware', 'Anonymous'),
        'Editor' => array('ONSBKS_Slots\RestApi\Middleware', 'Editor'),
        'Admin' => array('ONSBKS_Slots\RestApi\Middleware', 'Admin'),
        'User' => array('ONSBKS_Slots\RestApi\Middleware', 'User'),
    ];

    public static function GET($route_ref, $controller_ref, $perm_ref): void
    {
        Router::REQUEST('GET', $route_ref, $controller_ref, $perm_ref);
    }

    public static function POST($route_ref, $controller_ref, $perm_ref): void
    {
        Router::REQUEST('POST', $route_ref, $controller_ref, $perm_ref);
    }


    public static function REQUEST( $method, $route_ref, $controller_ref, $perm_ref ): void
    {
        if($perm_ref == null) $perm_ref = Router::$AUTH['Anonymous'];
        add_action( 'rest_api_init',  function () use ($method, $route_ref, $controller_ref, $perm_ref) {
            register_rest_route( self::$base_namespace, $route_ref, array(
                'methods' => $method,
                'callback' => $controller_ref,
                'permission_callback' => $perm_ref
            ) );
        });
    }

}