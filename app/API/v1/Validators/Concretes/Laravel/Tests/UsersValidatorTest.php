<?php

namespace App\API\v1\Validators\Concretes\Laravel\Tests ;

use App\API\v1\Validators\Concretes\Laravel\Validators\UsersValidator ;
use App\API\v1\Validators\Exceptions\InvalidInputException ;
use Tests\TestCase ;

/**
 * @codeCoverageIgnore
 */
class UsersValidatorTest extends TestCase
{

	public function testCreate_ReturnsNullForValidInputs ()
	{
		$data = [
			'user_key' => 'the_key' ,
			'user_secret' => 'the_secret' ,
			] ;

		$validator = new UsersValidator() ;

		$result = $validator -> create ( $data ) ;

		$this -> assertNull ( $result ) ;
	}

	public function testCreate_ThrowsInvalidInputExceptionForInvalidInputs ()
	{
		$data = [] ;

		$validator = new UsersValidator() ;

		try
		{
			$validator -> create ( $data ) ;
		} catch ( InvalidInputException $ex )
		{
			$this -> assertInstanceOf ( InvalidInputException::class , $ex ) ;

			$this -> assertEquals ( [
				'user_key' => [
					'required' ,
				] ,
				'user_secret' => [
					'required' ,
				] ,
				] , $ex -> getErrors () ) ;
		}
	}

}
