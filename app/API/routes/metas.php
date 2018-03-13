<?php

Route::get ( 'metakeys' , 'App\API\Controllers\MetasController@all' )
	-> name ( 'metas.all' ) ;

Route::get ( 'metas' , 'App\API\Controllers\MetasController@getMetas' )
	-> name ( 'metas.get.all' ) ;

Route::get ( 'metas/{metaKey}' , 'App\API\Controllers\MetasController@getMeta' )
	-> name ( 'meta.get.one' ) ;

Route::post ( 'metas' , 'App\API\Controllers\MetasController@saveMetas' )
	-> name ( 'meta.save.one' ) ;
