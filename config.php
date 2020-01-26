<?php

$baseUrl = 'https://nextar.flip.id';

return [

    'database' => [
        'name' => 'mysql',
        'db' => 'flip',
        'host' => 'localhost',
        'username' => 'root',
        'password' => ''
    ],
    'banks' => [
        'BNI',
        'BCA',
        'BRI',
        'Mandiri'
    ],
    'credential' => [
        'user' => 'HyzioY7LP6ZoO7nTYKbG8O4ISkyWnX1JvAEVAhtWKZumooCzqp41',
        'password' => '',
        'disburseUrl' => $baseUrl . '/disburse',
        'disburseStatusUrl' => $baseUrl . '/disburse/'
    ]

];