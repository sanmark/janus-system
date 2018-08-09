<?php

use App\API\Controllers\MetaKeysController;
use Illuminate\Routing\Router;

Route::group([
    'prefix' => 'metakeys' ,
], function (Router $r) {
        $controller = MetaKeysController::class . '@';

        $r
        -> get('', $controller . 'get')
        -> name('api.metakeys.get');
    });
