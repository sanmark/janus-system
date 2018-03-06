<?php

namespace App\Handlers\Tests ;

use App\Handlers\AuthSessionsHandler ;
use App\Handlers\UsersHandler ;
use App\Models\AuthSession ;
use App\Models\User ;
use App\Repos\Contracts\IAuthSessionsRepo ;
use Mockery ;
use Tests\TestCase ;

/**
 * @codeCoverageIgnore
 */
class AuthSessionsHandlerTest extends TestCase
{

	public function testCreateOk ()
	{
		$mockUser = Mockery::mock ( User::class ) ;
		$mockUsersHandler = Mockery::mock ( UsersHandler::class ) ;
		$mockIAuthSessionsRepo = Mockery::mock ( IAuthSessionsRepo::class ) ;
		$mockAuthSession = Mockery::mock ( AuthSession::class ) ;

		$mockUser -> id = 149 ;

		$mockUsersHandler
			-> shouldReceive ( 'getUserIfCredentialsValid' )
			-> withArgs ( [
				'the_key' ,
				'the_secret' ,
			] )
			-> andReturn ( $mockUser ) ;

		$mockIAuthSessionsRepo
			-> shouldReceive ( 'create' )
			-> withArgs ( [ 149 ] )
			-> andReturn ( $mockAuthSession ) ;

		$authSessionsHandler = new AuthSessionsHandler ( $mockIAuthSessionsRepo , $mockUsersHandler ) ;

		$response = $authSessionsHandler -> create ( 'the_key' , 'the_secret' ) ;

		$this -> assertSame ( $mockAuthSession , $response ) ;
	}

}
