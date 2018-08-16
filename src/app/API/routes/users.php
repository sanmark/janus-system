<?php

use App\API\Controllers\UsersController;
use Illuminate\Routing\Router;

\Route::group([
    'prefix' => 'users',
], function (Router $router) {
    $controller = UsersController::class . '@';

    $router
        -> get('', $controller . 'all')
        -> name('api.users.all');

    $router
        -> post('', $controller . 'create')
        -> name('api.users.create');

    $router
        -> get('/by-key/{key}', $controller . 'byKeyGet')
        -> name('api.users.by-key');

    $router
        -> get('{id}', $controller . 'get')
        -> name('api.users.get');

    $router
        -> patch('{id}', $controller . 'update')
        -> name('api.users.update');

    $router
        -> get('{id}/metas', $controller . 'metasAll')
        -> name('api.users.metas.all');

    $router
        -> get('{id}/metas/{key}', $controller . 'metasOne')
        -> name('api.users.metas.one');

    $router
        -> patch('{id}/metas/{key}', $controller . 'metasUpdate')
        -> name('api.users.metas.update');

    $router
        -> post('{id}/user-secret-reset-requests', $controller . 'userSecretResetRequestsCreate')
        -> name('api.users.user-secret-reset-requests.create');

    $router
        -> post('{id}/user-secret-reset-requests/execute', $controller . 'userSecretResetRequestsExecute')
        -> name('api.users.user-secret-reset-requests.execute');
});
