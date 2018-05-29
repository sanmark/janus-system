<?php

namespace App\API\Validators\Concretes\Laravel\Tests ;

use App\API\Validators\Concretes\Laravel\Validators\AuthSessionsValidator ;
use App\API\Validators\Exceptions\InvalidInputException ;
use Tests\TestCase ;

/**
 * @codeCoverageIgnore
 */
class AuthSessionsValidatorTest extends TestCase
{

	public function testCreate_ReturnsNullForValidInputs ()
	{
		$data = [
			'user_key' => 'the_key' ,
			'user_secret' => 'the_secret' ,
			] ;

		$validator = new AuthSessionsValidator() ;

		$result = $validator -> create ( $data ) ;

		$this -> assertNull ( $result ) ;
	}

	public function testCreate_ThrowsInvalidInputExceptionForInvalidInputs ()
	{
		$this -> expectException ( InvalidInputException::class ) ;

		$validator = new AuthSessionsValidator() ;

		try
		{
			$validator -> create ( [] ) ;
		} catch ( InvalidInputException $ex )
		{
			$this -> assertEquals ( [
				'user_key' => [
					'required' ,
				] ,
				'user_secret' => [
					'required' ,
				] ,
				] , $ex -> getErrors () ) ;

			throw $ex ;
		}
	}

}
