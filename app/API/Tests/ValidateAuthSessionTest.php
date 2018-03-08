<?php

namespace App\API\Tests ;

use Tests\TestCase ;

/**
 * @codeCoverageIgnore
 */
class ValidateAuthSessionTest extends TestCase
{

	public function testUserCanValidateAuthSession ()
	{
		$this -> seedDb () ;
		$this
			-> withHeader ( 'x-lk-sanmark-janus-sessionkey' , 'the_auth_session_key' )
			-> get ( 'api/auth-sessions/validate' )
			-> assertStatus ( 200 )
			-> assertJsonStructure ( [
				'data' => [
					'id' ,
					'key' ,
					'user_id' ,
					'created_at' ,
					'updated_at' ,
				] ,
			] ) ;
	}

	public function testSystemRejectsNullSessionKey ()
	{
		$this -> seedDb () ;
		$this
			-> get ( 'api/auth-sessions/validate' )
			-> assertStatus ( 401 )
			-> assertJsonStructure ( [] ) ;
	}

	public function testSystemRejectsInvalidSessionKey ()
	{
		$this -> seedDb () ;
		$this
			-> withHeader ( 'x-lk-sanmark-janus-sessionkey' , 'wrong' )
			-> get ( 'api/auth-sessions/validate' )
			-> assertStatus ( 401 )
			-> assertJsonStructure ( [] ) ;
	}

	public function testSystemRejectsExpiredSessionKey ()
	{
		$this -> seedDb () ;
		$this
			-> withHeader ( 'x-lk-sanmark-janus-sessionkey' , 'the_auth_session_key_expired' )
			-> get ( 'api/auth-sessions/validate' )
			-> assertStatus ( 401 )
			-> assertJsonStructure ( [] ) ;
	}

}
