<?php

use App\API\v1\Controllers\AuthSessionsController ;
use Illuminate\Routing\Router ;

Route::group ( [
	'prefix' => 'auth-sessions'
	] , function(Router $router)
{
	$methodPrefix = AuthSessionsController::class . '@' ;

	$router
		-> post ( '' , $methodPrefix . 'create' )
		-> name ( 'auth-sessions.create' ) ;

	$router
		-> get ( 'validate' , $methodPrefix . 'validate' )
		-> name ( 'auth-sessions.create' ) ;
} ) ;
