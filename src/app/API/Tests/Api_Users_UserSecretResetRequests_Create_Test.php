<?php

namespace App\API\Tests ;

use Tests\TestCase ;

/**
 * @codeCoverageIgnore
 */
class Api_Users_UserSecretResetRequests_Create_Test extends TestCase
{

	private $url ;

	protected function setUp ()
	{
		parent::setUp () ;

		$this -> seedDb () ;
		$this -> url = 'api/users/1/user-secret-reset-requests' ;
	}

	public function testSuccess ()
	{
		$this
			-> postWithValidAppKeyAndSecretHash ( $this -> url )
			-> assertStatus ( 201 )
			-> assertJsonStructure ( [
				"data" => [
					"id" ,
					"user_id" ,
					"token" ,
					"created_at" ,
					"updated_at" ,
				]
			] ) ;
	}

	public function testInvalidUserIdCausesError ()
	{
		$url = 'api/users/149/user-secret-reset-requests' ;

		$this
			-> postWithValidAppKeyAndSecretHash ( $url )
			-> assertStatus ( 404 )
			-> assertJson ( [
				"errors" => [] ,
			] ) ;
	}

	public function testInvalidAppKeyIsRejected ()
	{
		$this
			-> postWithInvalidAppKeyAndSecretHash ( $this -> url )
			-> assertStatus ( 401 )
			-> assertJson ( [
				'errors' => [] ,
			] ) ;
	}

	public function testInvalidSecretHashIsRejected ()
	{
		$this
			-> postWithValidAppKeyAndInvalidSecretHash ( $this -> url )
			-> assertStatus ( 401 )
			-> assertJson ( [
				'errors' => [] ,
			] ) ;
	}

	public function testNoAppKeyIsRejected ()
	{
		$this
			-> post ( $this -> url )
			-> assertStatus ( 401 )
			-> assertJson ( [
				'errors' => [] ,
			] ) ;
	}

}
