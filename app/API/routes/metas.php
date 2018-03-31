<?php

use App\API\Controllers\MetasController ;
use Illuminate\Routing\Router ;

Route::group ( [
	'prefix' => 'metas' ,
	] , function(Router $r)
{
	$controller = MetasController::class . '@' ;

	$r
		-> get ( '' , $controller . 'get' )
		-> name ( 'api.metas.get' ) ;

	$r
		-> post ( '' , $controller . 'create' )
		-> name ( 'api.metas.create' ) ;

	$r
		-> get ( '{key}' , $controller . 'getMeta' )
		-> name ( 'api.metas.value' ) ;

	$r
		-> get ( '{key}/value/{value}/users' , $controller . 'metaValueUsersGet' )
		-> name ( 'api.metas.value.users' ) ;
} ) ;
