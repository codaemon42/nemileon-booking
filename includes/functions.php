<?php
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;
use JetBrains\PhpStorm\ArrayShape;

function onsbks_get_products(): array
{
    $args = array(
        'post_type'      => 'product',
        'type'   => 'booking_slot',
        'posts_per_page' => -1,
    );

    return wc_get_products($args);

}


//#[ArrayShape(['success' => "bool|mixed", 'result' => "", 'message' => "mixed|string"])]
function prepare_result($data, $message = 'Successfully Done', $success = true): array
{
    return array(
        'success' => $success,
        'result' => $data,
        'message' => $message
    );
}

function onsbks_get_jwt_secret(): string
{
    return 'your_secret_key';
}

function onsbks_create_jwt(): string
{
    $userId = get_current_user_id();
    $payload = array(
        'anonymous' => $userId == null,
        'user_id' => $userId,
        'nonce' => wp_create_nonce('JWT_NONCE'),
        'exp' => time() + (60 * 60 * 24), // Token expiration time (e.g., 24 hours from now)
    );

    $token =  JWT::encode($payload, onsbks_get_jwt_secret(), 'HS256', onsbks_get_jwt_secret());
    return $token;
}

function onsbks_decode_jwt($token): array
{
    $secret_key = onsbks_get_jwt_secret();
    $result = array();
    try {
        $decoded_token = JWT::decode($token, new Key($secret_key, 'HS256'));

        $result['anonymous'] = $decoded_token->anonymous;
        $result['user_id'] = $decoded_token->user_id;
        $result['nonce'] = $decoded_token->nonce;
        $result['exp'] = $decoded_token->exp;

    } catch (Exception $e) {
        // $result = array();
    }

    return $result;
}

function onsbks_verify_jwt($token): bool
{
    $result = onsbks_decode_jwt($token);


    if(count($result) == 0) {
        return false;
    }

    if($result['anonymous']) {
        return wp_verify_nonce($result['nonce'], 'JWT_NONCE');
    }

    return true;
}