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
        '/api/v1/register-user',
        '/api/v1/login',
        '/api/v1/password-reset-link',
        '/api/v1/logout',
        '/api/v1/password-update',
        '/api/v1/update-profile',
        '/api/v1/add-vehicles',
        '/api/v1/vehicles/*',
        '/api/v1/delete-vehicle/*',
        '/api/v1/add-kids',
        '/api/v1/kid/*',
        '/api/v1/delete-kid/*',
        '/api/v1/update-profile/*'
        // Add the route you want to exclude here
    ];
}
