<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    // protected $except = [
    //     //
    // ];
    protected $except = [
        '/register-user',
        '/login',
        '/password-reset-link',
        '/logout',
        '/password-update',
        '/update-profile',
        '/add-vehicles',
        '/vehicles/*',
        '/delete-vehicle/*',
        '/add-kids',
        '/kid/*',
        '/delete-kid/*',
        '/update-profile/*'
        // Add the route you want to exclude here
    ];
}
