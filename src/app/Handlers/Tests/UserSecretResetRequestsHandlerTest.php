<?php

namespace App\Handlers\Tests ;

use App\Handlers\UserSecretResetRequestsHandler ;
use App\Handlers\UsersHandler ;
use App\Models\User ;
use App\Models\UserSecretResetRequest ;
use App\Repos\Contracts\IUserSecretResetRequestsRepo ;
use App\Repos\Exceptions\RecordNotFoundException ;
use Tests\TestCase ;

class UserSecretResetRequestsHandlerTest extends TestCase
{

	public function test_create_ok ()
	{
		$userSecretResetRequestsRepo = $this -> mock ( IUserSecretResetRequestsRepo::class ) ;
		$usersHandler = $this -> mock ( UsersHandler::class ) ;

		$userSecretResetRequestsHandler = new UserSecretResetRequestsHandler ( $userSecretResetRequestsRepo , $usersHandler ) ;

		$user = $this -> mock ( User::class ) ;
		$user -> id = 149 ;

		$usersHandler -> shouldReceive ( 'get' )
			-> withArgs ( [
				149 ,
			] )
			-> andReturn ( $user ) ;

		$userSecretResetRequest = $this -> mock ( UserSecretResetRequest::class ) ;

		$userSecretResetRequestsRepo -> shouldReceive ( 'create' )
			-> withArgs ( [
				149 ,
			] )
			-> andReturn ( $userSecretResetRequest ) ;

		$response = $userSecretResetRequestsHandler -> create ( 149 ) ;

		$this -> assertSame ( $userSecretResetRequest , $response ) ;
	}

	public function test_execute_ok ()
	{
		$userSecretResetRequestsRepo = $this -> mock ( IUserSecretResetRequestsRepo::class ) ;
		$usersHandler = $this -> mock ( UsersHandler::class ) ;

		$userSecretResetRequestsHandler = new UserSecretResetRequestsHandler ( $userSecretResetRequestsRepo , $usersHandler ) ;

		$user = $this -> mock ( User::class ) ;
		$user -> id = 149 ;

		$usersHandler -> shouldReceive ( 'get' )
			-> withArgs ( [
				149 ,
			] )
			-> andReturn ( $user ) ;

		$userSecretResetRequest = $this -> mock ( UserSecretResetRequest::class ) ;
		$userSecretResetRequest -> user_id = 149 ;

		$userSecretResetRequestsRepo -> shouldReceive ( 'getByToken' )
			-> withArgs ( [
				'the-token' ,
			] )
			-> andReturn ( $userSecretResetRequest ) ;

		$usersHandler -> shouldReceive ( 'update' )
			-> withArgs ( [
				149 ,
				[
					'user_secret' => 'the-secret' ,
				]
			] )
			-> andReturn ( $user ) ;

		$userSecretResetRequestsRepo -> shouldReceive ( 'deleteOfUser' )
			-> withArgs ( [
				149 ,
			] )
			-> andReturn ( 150 ) ;

		$response = $userSecretResetRequestsHandler -> execute ( 149 , 'the-token' , 'the-secret' ) ;

		$this -> assertSame ( $user , $response ) ;
	}

	public function test_execute_mismatchingUserIdThrowsRecordNotFoundException ()
	{
		$userSecretResetRequestsRepo = $this -> mock ( IUserSecretResetRequestsRepo::class ) ;
		$usersHandler = $this -> mock ( UsersHandler::class ) ;

		$userSecretResetRequestsHandler = new UserSecretResetRequestsHandler ( $userSecretResetRequestsRepo , $usersHandler ) ;

		$user = $this -> mock ( User::class ) ;
		$user -> id = 149 ;

		$usersHandler -> shouldReceive ( 'get' )
			-> withArgs ( [
				149 ,
			] )
			-> andReturn ( $user ) ;

		$userSecretResetRequest = $this -> mock ( UserSecretResetRequest::class ) ;
		$userSecretResetRequest -> user_id = 150 ;

		$userSecretResetRequestsRepo -> shouldReceive ( 'getByToken' )
			-> withArgs ( [
				'the-token' ,
			] )
			-> andReturn ( $userSecretResetRequest ) ;

		$this -> expectException ( RecordNotFoundException::class ) ;

		$userSecretResetRequestsHandler -> execute ( 149 , 'the-token' , 'the-secret' ) ;
	}

}
