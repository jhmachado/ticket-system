<?php

return [
    'host' => env('KEY_CLOAK_HOST', 'http://localhost/'),
    'client_id' => env('KEY_CLOAK_CLIENT_ID', 'client_id'),
    'secret' => env('KEY_CLOAK_SECRET', 'secret'),
    'scope' => env('KEY_CLOAK_SCOPE', 'openid'),
    'grant_type' => env('KEY_CLOAK_GRANT_TYPE', 'password'),
];
