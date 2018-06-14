<?php

namespace App\API\Validators\Concretes\Laravel\Validators\Tests ;

use App\API\Validators\Concretes\Laravel\Validators\MetasValidator ;
use App\API\Validators\Exceptions\InvalidInputException ;
use Tests\TestCase ;

/**
 * @codeCoverageIgnore
 */
class MetaValidatorTest extends TestCase
{

	public function test_createByUserIdAndMetaKey_returnsNullForValidInput ()
	{
		$data = [
			'value' => 'new-value' ,
			] ;

		$validator = new MetasValidator() ;

		$response = $validator -> createByUserIdAndMetaKey ( $data ) ;

		$this -> assertNull ( $response ) ;
	}

	public function test_createByUserIdAndMetaKey_throwsInvalidInputExceptionForInvalidInputs ()
	{
		try
		{
			$validator = new MetasValidator() ;

			$data = [] ;
			$validator -> createByUserIdAndMetaKey ( $data ) ;
		} catch ( InvalidInputException $ex )
		{
			$this -> assertInstanceOf ( InvalidInputException::class , $ex ) ;

			$this -> assertEquals ( [
				'value' => [
					'required' ,
				] ,
				] , $ex -> getErrors () ) ;
		}
	}

}
