<?php

namespace App\API\Tests ;

use Tests\TestCase ;

/**
 * @codeCoverageIgnore
 */
class Api_Users_Metas_One_Test extends TestCase
{

	private $url ;

	protected function setUp ()
	{
		parent::setUp () ;

		$this -> seedDb () ;
		$this -> url = 'api/users/1/metas/demo-meta-1' ;
	}

	public function testCorrectDataAreReturned ()
	{
		$this
			-> getWithValidAppKeyAndSecretHash ( $this -> url )
			-> assertStatus ( 200 )
			-> assertJson ( [
				'data' => [
					'meta_key' => 'demo-meta-1' ,
					'value' => 'demo-meta-1-value' ,
					'user_id' => '1' ,
				]
			] ) ;
	}

	public function testInvalidMetaKeyCauseError ()
	{
		$url = 'api/users/1/metas/non-existing-meta' ;

		$this
			-> getWithValidAppKeyAndSecretHash ( $url )
			-> assertStatus ( 404 )
			-> assertJson ( [
				'errors' => [
				] ,
			] ) ;
	}
	
	public function testInvalidUserIdCauseError ()
	{
		$url = 'api/users/149/metas/demo-meta-1' ;

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
