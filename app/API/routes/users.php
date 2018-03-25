<?php

use App\API\Controllers\UsersController ;
use Illuminate\Routing\Router ;

\Route::group ( [
	'prefix' => 'users'
	] , function(Router $router)
{
	$controller = UsersController::class . '@' ;

	$router
		-> post ( '' , $controller . 'create' )
		-> name ( 'api.users.create' ) ;

	$router
		-> patch ( '{id}' , $controller . 'update' )
		-> name ( 'api.users.update' ) ;

	$router
		-> post ( '{id}/user-secret-reset-requests' , $controller . 'userSecretResetRequestsCreate' )
		-> name ( 'api.users.user-secret-reset-requests.create' ) ;

	$router
		-> post ( '{id}/user-secret-reset-requests/execute' , $controller . 'userSecretResetRequestsExecute' )
		-> name ( 'api.users.user-secret-reset-requests.execute' ) ;
} ) ;
