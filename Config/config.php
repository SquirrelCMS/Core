<?php

return [
    'name' => 'Core',

    'middlewareGroups' => [
        'web' => [
            \Modules\Core\Http\Middleware\EncryptCookies::class,
            \Modules\Core\Http\Middleware\VerifyCsrfToken::class,
        ]
    ],

    'routeMiddleware' => [
        'guest' => \Modules\Core\Http\Middleware\RedirectIfAuthenticated::class,
    ],
];
