<?php
namespace Valyria\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack
     *
     * @var array
     */
    protected $middleware = [
        'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
        'Barryvdh\HttpCache\Middleware\CacheRequests',
    ];

    /**
     * The application's route middleware
     *
     * @var array
     */
    protected $routeMiddleware = [
        //'auth' => 'Valyria\Http\Middleware\Authenticate',
        'auth.basic' => 'Valyria\Http\Middleware\AuthBasic',
    ];
}
