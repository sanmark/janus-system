<?php

namespace App\API\Controllers\Tests ;

use App\API\Controllers\MetaKeysController ;
use App\Handlers\MetaKeysHandler ;
use Tests\TestCase ;

/**
 * @codeCoverageIgnore
 */
class MetaKeysControllerTest extends TestCase
{

	public function test_get_ok ()
	{
		$metaKeysHandler = $this -> mock ( MetaKeysHandler::class ) ;

		$metaKeysHandler -> shouldReceive ( 'all' )
			-> andReturn ( 149 ) ;

		$metaKeysController = new MetaKeysController ( $metaKeysHandler ) ;

		$response = $metaKeysController -> get () ;

		$this -> assertInstanceOf ( \Illuminate\Http\JsonResponse::class , $response ) ;
		$this -> assertEquals ( 200 , $response -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				'data' => 149 ,
			] , $response -> getData () ) ;
	}

}
