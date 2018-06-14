<?php

namespace App\API\Tests ;

use Tests\TestCase ;

/**
 * @codeCoverageIgnore
 */
class Api_Users_Metas_Update_Test extends TestCase
{

	private $url ;

	protected function setUp ()
	{
		parent::setUp () ;

		$this -> seedDb () ;
		$this -> url = 'api/users/1/metas/demo-meta-1' ;
	}

	public function testEmptyInputCausesError ()
	{
		$this
			-> patchWithValidAppKeyAndSecretHash ( $this -> url )
			-> assertStatus ( 400 )
			-> assertJson ( [
				'errors' => [
					'value' => [
						'required' ,
					] ,
				] ,
			] ) ;
	}

	public function testSuccess ()
	{
		$data = [
			'value' => 'the-value' ,
			] ;

		$this
			-> patchWithValidAppKeyAndSecretHash ( $this -> url , $data )
			-> assertStatus ( 200 )
			-> assertJson ( [
				'data' => [
					'id' => 1 ,
					'meta_key_id' => 1 ,
					'user_id' => 1 ,
					'value' => 'the-value' ,
					'created_at' => [] ,
					'updated_at' => [] ,
				] ,
			] ) ;
	}

	public function testInvalidMetaKeyCausesError ()
	{
		$url = 'api/users/1/metas/non-existing-meta' ;

		$this
			-> patchWithValidAppKeyAndSecretHash ( $url )
			-> assertStatus ( 404 )
			-> assertJson ( [
				'errors' => [
				] ,
			] ) ;
	}

	public function testInvalidUserIdCausesError ()
	{
		$url = 'api/users/149/metas/demo-meta-1' ;

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
