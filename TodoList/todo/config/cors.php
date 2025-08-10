<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],   // Allow all origins for dev/testing
    'allowed_headers' => ['*'],
    'supports_credentials' => false, // kalau pakai token tanpa cookie bisa false
];
