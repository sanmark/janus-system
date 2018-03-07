<?php

namespace App\API\v1\Tests\Feature ;

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
			-> get ( 'api/v1/auth-sessions/validate' )
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

	public function testSystemRejectsInvalidSessionKey ()
	{
		$this -> seedDb () ;
		$this
			-> withHeader ( 'x-lk-sanmark-janus-sessionkey' , 'wrong' )
			-> get ( 'api/v1/auth-sessions/validate' )
			-> assertStatus ( 401 )
			-> assertJsonStructure ( [] ) ;
	}

}
