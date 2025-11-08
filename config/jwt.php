<?php

return [
    'secret' => env('JWT_SECRET', 'b1946ac92492d2347c6235b4d2611184f1e3a92d7e94cb9e92a0d6d2f89f9a0c'),
    // TTL mặc định (không Remember)
    'ttl_minutes' => env('JWT_TTL_MINUTES', 120), // 2 giờ
    // TTL khi Remember
    'ttl_remember_minutes' => env('JWT_TTL_REMEMBER_MINUTES', 60 * 24 * 7), // 7 ngày
    'issuer' => env('APP_URL', 'http://localhost'),
    'audience' => env('APP_URL', 'http://localhost'),
];



