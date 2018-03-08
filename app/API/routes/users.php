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
} ) ;
