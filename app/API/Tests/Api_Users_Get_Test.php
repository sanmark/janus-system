<?php

namespace App\API\Tests ;

use Tests\TestCase ;

/**
 * @codeCoverageIgnore
 */
class Api_Users_Get_Test extends TestCase
{

	private $url ;

	protected function setUp ()
	{
		parent::setUp () ;

		$this -> seedDb () ;
		$this -> url = 'api/users/1' ;
	}

	public function testCorrectDataAreReturned ()
	{
		$this
			-> getWithValidAppKeyAndSecretHash ( $this -> url )
			-> assertStatus ( 200 )
			-> assertJson ( [
				'data' => [
					'id' => '1' ,
					'key' => 'user1' ,
				] ,
			] ) ;
	}

	public function testInvalidUserKeyReturnsError ()
	{
		$url = 'api/users/999' ;

		$this
			-> getWithValidAppKeyAndSecretHash ( $url )
			-> assertStatus ( 404 )
			-> assertJson ( [
				'errors' => [
				] ,
			] ) ;
	}

	public function testInvalidAppKeyIsRejected ()
	{
		$this
			-> getWithInvalidAppKeyAndSecretHash ( $this -> url )
			-> assertStatus ( 401 )
			-> assertJson ( [
				'errors' => [] ,
			] ) ;
	}

	public function testNoAppKeyIsRejected ()
	{
		$this
			-> get ( $this -> url )
			-> assertStatus ( 401 )
			-> assertJson ( [
				'errors' => [] ,
			] ) ;
	}

}
