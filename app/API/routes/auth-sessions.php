<?php

use App\API\Controllers\AuthSessionsController ;
use Illuminate\Routing\Router ;

Route::group ( [
	'prefix' => 'auth-sessions'
	] , function(Router $router)
{
	$methodPrefix = AuthSessionsController::class . '@' ;

	$router
		-> post ( '' , $methodPrefix . 'create' )
		-> name ( 'api.auth-sessions.create' ) ;

	$router
		-> get ( 'validate' , $methodPrefix . 'validate' )
		-> name ( 'api.auth-sessions.validate' ) ;
} ) ;
