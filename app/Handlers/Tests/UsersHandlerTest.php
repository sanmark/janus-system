<?php

namespace App\Handlers\Tests ;

use App\Handlers\UsersHandler ;
use App\Models\User ;
use App\Repos\Contracts\IUsersRepo ;
use Mockery ;
use Tests\TestCase ;
use function dd ;

/**
 * @codeCoverageIgnore
 */
class UsersHandlerTest extends TestCase
{

	public function testCreateOk ()
	{
		$mockIUsersRepo = Mockery::mock ( IUsersRepo::class ) ;
		$userKey = $this -> faker () -> userName ;
		$userSecret = $this -> faker () -> password ;
		$mockUserModel = Mockery::mock ( User::class ) ;

		$mockIUsersRepo
			-> shouldReceive ( 'create' )
			-> withArgs ( [
				$userKey ,
				$userSecret ,
			] )
			-> andReturn ( $mockUserModel ) ;

		$usersHandler = new UsersHandler ( $mockIUsersRepo ) ;

		$response = $usersHandler -> create ( $userKey , $userSecret ) ;

		$this -> assertSame ( $mockUserModel , $response ) ;
	}

}
