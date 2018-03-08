<?php

namespace App\API\Tests ;

use Tests\TestCase ;
use function dd ;

/**
 * @codeCoverageIgnore
 */
class UserLoginTest extends TestCase
{

	public function testUserCanLogin ()
	{
		$this -> seedDb () ;

		$data = [
			'user_key' => 'user1' ,
			'user_secret' => 'sec1' ,
			] ;

		$this
			-> post ( 'api/auth-sessions' , $data )
			-> assertStatus ( 201 )
			-> assertJsonStructure ( [
				'data' => [
					'key' ,
				] ,
			] ) ;
	}

	public function testSystemValidatesUserInputs ()
	{
		$this
			-> post ( 'api/auth-sessions' )
			-> assertStatus ( 400 )
			-> assertJson ( [
				'errors' => [
					'user_key' => [
						'required' ,
					] ,
					'user_secret' => [
						'required' ,
					] ,
				] ,
			] ) ;
	}

	public function testSystemRejectsInvalidUserKeys ()
	{
		$this -> seedDb () ;

		$data = [
			'user_key' => 'wrong1' ,
			'user_secret' => 'sec1' ,
			] ;

		$this
			-> post ( 'api/auth-sessions' , $data )
			-> assertStatus ( 401 ) ;
	}

	public function testSystemRejectsInvalidUserSecrets ()
	{
		$this -> seedDb () ;

		$data = [
			'user_key' => 'user1' ,
			'user_secret' => 'wrong1' ,
			] ;

		$this
			-> post ( 'api/auth-sessions' , $data )
			-> assertStatus ( 401 ) ;
	}

}
