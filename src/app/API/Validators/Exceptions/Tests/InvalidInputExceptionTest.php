<?php

namespace App\API\Validators\Exceptions\Tests ;

use App\API\Validators\Exceptions\InvalidInputException ;
use Tests\TestCase ;

/**
 * @codeCoverageIgnore
 */
class InvalidInputExceptionTest extends TestCase
{

	public function test_getErrors_ok ()
	{
		$invalidInputException = new InvalidInputException ( [ 149 ] ) ;

		$response = $invalidInputException -> getErrors () ;

		$this -> assertSame ( [
			149 ,
			] , $response ) ;
	}

}
