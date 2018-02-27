<?php

use Illuminate\Routing\Router ;

Route::group ( [
	'prefix' => 'users' ,
	] , function(Router $router)
{
	$controller = App\Http\Controllers\API\v1\UsersController::class . '@' ;

	$router
		-> post ( '' , $controller . 'create' )
		-> name ( 'users.create' ) ;
} ) ;


