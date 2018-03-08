<?php

namespace App\API\Tests ;

use Tests\TestCase ;

/**
 * @codeCoverageIgnore
 */
class UserRegistrationTest extends TestCase
{

	public function testUserCanRegister ()
	{
		$data = [
			'user_key' => $this -> faker () -> userName ,
			'user_secret' => $this -> faker () -> password ,
			] ;

		$this
			-> post ( 'api/users' , $data )
			-> assertStatus ( 201 )
			-> assertJson ( [
				'data' => [
					'key' => $data[ 'user_key' ] ,
				] ,
			] )
			-> assertJsonStructure ( [
				'data' => [
					'id' ,
				] ,
			] ) ;
	}

	public function testSystemValidatesUserInputs ()
	{
		$this
			-> post ( 'api/users' )
			-> assertStatus ( 400 )
			-> assertJson ( [
				'errors' => [
					'user_key' => [
						'required'
					] ,
					'user_secret' => [
						'required'
					] ,
				] ,
			] ) ;
	}

	public function testSystemRejectsDuplicateUserKeys ()
	{
		$data = [
			'user_key' => $this -> faker () -> userName ,
			'user_secret' => $this -> faker () -> password ,
			] ;

		$this -> post ( 'api/users' , $data ) ;

		$this
			-> post ( 'api/users' , $data )
			-> assertStatus ( 409 )
			-> assertJson ( [
				'errors' => [
					'user_key' => 'duplicate' ,
				] ,
			] ) ;
	}

}
