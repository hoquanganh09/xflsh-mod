<?php

namespace App\Http\Routes;

use Illuminate\Contracts\Routing\Registrar;

class ClientRoute
{
    public function map(Registrar $router)
    {
        $router->group([
            'prefix' => 'client',
            'middleware' => ['throttle:subscribe', 'client']
        ], function ($router) {
            // Client
            $router->get('/subscribe', 'Client\\ClientController@subscribe');
        });
    }
}