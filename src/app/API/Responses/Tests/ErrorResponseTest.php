<?php

namespace App\API\Responses\Tests ;

use App\API\Responses\ErrorResponse ;
use Illuminate\Http\JsonResponse ;
use Tests\TestCase ;

/**
 * @codeCoverageIgnore
 */
class ErrorResponseTest extends TestCase
{

	public function test_getOutput_ok ()
	{
		$errorResponse = new ErrorResponse ( 149 ) ;

		$response = $errorResponse -> getOutput () ;

		$this -> assertSame ( [
			'errors' => 149 ,
			] , $response ) ;
	}

	public function test_getResponse_ok ()
	{
		$errorResponse = new ErrorResponse ( 149 , 150 ) ;

		$response = $errorResponse -> getResponse () ;

		$this -> assertInstanceOf ( JsonResponse::class , $response ) ;
		$this -> assertEquals ( 150 , $response -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				'errors' => 149 ,
			] , $response -> getData () ) ;
	}

}
