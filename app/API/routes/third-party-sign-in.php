<?php

use App\API\Controllers\ThirdPartySignInController ;
use Illuminate\Routing\Router ;

Route::group ( [
	'prefix' => 'third-party-sign-in'
	] , function(Router $router)
{
	$methodPrefix = ThirdPartySignInController::class . '@' ;

	$router
		-> post ( 'google' , $methodPrefix . 'google' )
		-> name ( 'api.third-party-sign-in.google' ) ;
} ) ;
