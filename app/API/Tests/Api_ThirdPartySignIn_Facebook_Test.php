<?php

namespace App\API\Tests ;

use Tests\TestCase ;

/**
 * @codeCoverageIgnore
 */
class Api_ThirdPartySignIn_Facebook_Test extends TestCase
{

	private $url ;

	protected function setUp ()
	{
		parent::setUp () ;

		$this -> seedDb () ;
		$this -> url = 'api/third-party-sign-in/facebook' ;
	}

	public function testNoTokenCausesError ()
	{
		$this
			-> postWithValidAppKeyAndSecretHash ( $this -> url )
			-> assertStatus ( 400 )
			-> assertJson ( [
				'errors' => [
					'token' => [
						'required' ,
					] ,
				] ,
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
