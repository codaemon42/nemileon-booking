<?php
namespace ONSBKS_Slots\RestApi;

class Router
{
    public string $base_namespace = "onsbks/v2";

    public array $AUTH = [
        'Test' => '__return_true',
        'Anonymous' => array('ONSBKS_Slots\RestApi\Middleware', 'Anonymous'),
        'Editor' => array('ONSBKS_Slots\RestApi\Middleware', 'Editor'),
        'Admin' => array('ONSBKS_Slots\RestApi\Middleware', 'Admin'),
        'User' => array('ONSBKS_Slots\RestApi\Middleware', 'User'),
    ];

    public function GET($route_ref, $controller_ref, $perm_ref): void
    {
        $this->REQUEST('GET', $route_ref, $controller_ref, $perm_ref);
    }

    public function POST($route_ref, $controller_ref, $perm_ref): void
    {
        Router::REQUEST('POST', $route_ref, $controller_ref, $perm_ref);
    }

    public function PUT($route_ref, $controller_ref, $perm_ref): void
    {
        Router::REQUEST('PUT', $route_ref, $controller_ref, $perm_ref);
    }

    public function DELETE($route_ref, $controller_ref, $perm_ref): void
    {
        Router::REQUEST('DELETE', $route_ref, $controller_ref, $perm_ref);
    }


    public function REQUEST( $method, $route_ref, $controller_ref, $perm_ref ): void
    {
        if($perm_ref == null) $perm_ref = $this->AUTH['Test'];
        if(function_exists('add_action')) {
            add_action( 'rest_api_init',  function () use ($method, $route_ref, $controller_ref, $perm_ref) {
                register_rest_route( $this->base_namespace, $route_ref, array(
                    'methods' => $method,
                    'callback' => $controller_ref,
                    'permission_callback' => $perm_ref
                ) );
            });
        }
    }

    public function set_auth($key, $value): void
    {
        $this->AUTH[$key] = $value;
    }

}