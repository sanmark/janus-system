<?php

namespace App\API\Tests ;

use Tests\TestCase ;

/**
 * @codeCoverageIgnore
 */
class Api_Users_Update_Test extends TestCase
{

	private $url ;

	protected function setUp ()
	{
		parent::setUp () ;

		$this -> seedDb () ;
		$this -> url = 'api/users/1' ;
	}

	public function testEmptyDataCauseSuccess ()
	{
		$this
			-> patchWithValidAppKeyAndSecretHash ( $this -> url )
			-> assertStatus ( 200 )
			-> assertJson ( [
				'data' => [
					'id' => 1 ,
					'key' => 'user1' ,
				] ,
			] ) ;
	}

	public function testCleanDataCauseSuccess ()
	{
		$data = [
			'user_secret' => 'valid-password' ,
			] ;

		$this
			-> patchWithValidAppKeyAndSecretHash ( $this -> url , $data )
			-> assertStatus ( 200 )
			-> assertJson ( [
				'data' => [
					'id' => 1 ,
					'key' => 'user1' ,
				] ,
			] ) ;
	}

	public function testInvalidUserIdCauseError ()
	{
		$url = 'api/users/149' ;

		$this
			-> patchWithValidAppKeyAndSecretHash ( $url )
			-> assertStatus ( 404 )
			-> assertJson ( [
				'errors' => [
				] ,
			] ) ;
	}

	public function testInvalidAppKeyIsRejected ()
	{
		$this
			-> patchWithInvalidAppKeyAndSecretHash ( $this -> url )
			-> assertStatus ( 401 )
			-> assertJson ( [
				'errors' => [] ,
			] ) ;
	}
	
	public function testInvalidSecretHashIsRejected ()
	{
		$this
			-> patchWithValidAppKeyAndInvalidSecretHash ( $this -> url )
			-> assertStatus ( 401 )
			-> assertJson ( [
				'errors' => [] ,
			] ) ;
	}

	public function testNoAppKeyIsRejected ()
	{
		$this
			-> patch ( $this -> url )
			-> assertStatus ( 401 )
			-> assertJson ( [
				'errors' => [] ,
			] ) ;
	}

}
