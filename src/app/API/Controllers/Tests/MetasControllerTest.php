<?php

namespace App\API\Controllers\Tests ;

use App\API\Controllers\MetasController ;
use App\Handlers\MetaKeysHandler ;
use Illuminate\Http\JsonResponse ;
use Tests\TestCase ;

/**
 * @codeCoverageIgnore
 */
class MetasControllerTest extends TestCase
{

	public function test_metaValueUsersGet_ok ()
	{
		$metaKeysHandler = $this -> mock ( MetaKeysHandler::class ) ;
		$metasController = new MetasController ( $metaKeysHandler ) ;

		$metaKeysHandler -> shouldReceive ( 'getUsersForMetaValue' )
			-> withArgs ( [
				'the-key' ,
				'the-value' ,
			] )
			-> andReturn ( [ 149 ] ) ;

		$response = $metasController -> metaValueUsersGet ( 'the-key' , 'the-value' ) ;

		$this -> assertInstanceOf ( JsonResponse::class , $response ) ;
		$this -> assertEquals ( 200 , $response -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				'data' => [
					149 ,
				] ,
			] , $response -> getData () ) ;
	}

}
