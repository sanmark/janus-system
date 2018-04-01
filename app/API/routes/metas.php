<?php

use App\API\Controllers\MetasController ;
use Illuminate\Routing\Router ;

Route::group ( [
	'prefix' => 'metas' ,
	] , function(Router $r)
{
	$controller = MetasController::class . '@' ;

	$r
		-> get ( '{key}/value/{value}/users' , $controller . 'metaValueUsersGet' )
		-> name ( 'api.metas.value.users' ) ;
} ) ;
