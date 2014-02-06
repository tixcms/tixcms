<?php

// массив разрешенных ip
$white_ips = array(
    '127.0.0.1',
    '192.168.1.'
);

// пользовательский ip
$user_ip = $_SERVER['REMOTE_ADDR'];

if( !valid_ip($user_ip, $white_ips) )
{
    header('HTTP/1.0 404 Not Found');
    exit;
}

// environment
define('ENVIRONMENT', 'development');

include('index.php');

function valid_ip($user_ip, $white_ips)
{
    $result = array_filter($white_ips, function($ip) use ($user_ip) {
        return strpos($user_ip, $ip) === 0;
    });
    
    return !empty($result);
}