<?php

namespace App\Handlers\Tests ;

use App\Handlers\AuthSessionsHandler ;
use App\Handlers\FacebookAccountsHandler ;
use App\Handlers\UsersHandler ;
use App\Models\AuthSession ;
use App\Models\FacebookAccount ;
use App\Models\User ;
use App\Repos\Contracts\IFacebookAccountsRepo ;
use App\Repos\Exceptions\RecordNotFoundException ;
use Carbon\Carbon ;
use Illuminate\Contracts\Hashing\Hasher ;
use Tests\TestCase ;
use function dd ;

class FacebookAccountsHandlerTest extends TestCase
{

	public function test_getAuthSession_forExistingFacebookAccount ()
	{
		$authSessionsHandler = $this -> mock ( AuthSessionsHandler::class ) ;
		$carbon = $this -> mock ( Carbon::class ) ;
		$facebookAccountsRepo = $this -> mock ( IFacebookAccountsRepo::class ) ;
		$hasher = $this -> mock ( Hasher::class ) ;
		$usersHandler = $this -> mock ( UsersHandler::class ) ;

		$facebookAccountsHandler = new FacebookAccountsHandler ( $authSessionsHandler , $carbon , $facebookAccountsRepo , $hasher , $usersHandler ) ;

		$facebookAccount = $this -> mock ( FacebookAccount::class ) ;
		$facebookAccount -> user_id = 149 ;

		$facebookAccountsRepo
			-> shouldReceive ( 'getByKey' )
			-> withArgs ( [ 'key' ] )
			-> andReturn ( $facebookAccount ) ;

		$user = $this -> mock ( User::class ) ;

		$usersHandler -> shouldReceive ( 'get' )
			-> withArgs ( [
				149 ,
			] )
			-> andReturn ( $user ) ;

		$authSession = $this -> mock ( AuthSession::class ) ;

		$authSessionsHandler -> shouldReceive ( 'createForUserObject' )
			-> withArgs ( [
				$user ,
			] )
			-> andReturn ( $authSession ) ;

		$response = $facebookAccountsHandler -> getAuthSession ( 'key' , 'firstname' ) ;

		$this -> assertSame ( $authSession , $response ) ;
	}

	public function test_getAuthSession_forNewFacebookAccount ()
	{
		$authSessionsHandler = $this -> mock ( AuthSessionsHandler::class ) ;
		$carbon = $this -> mock ( Carbon::class ) ;
		$facebookAccountsRepo = $this -> mock ( IFacebookAccountsRepo::class ) ;
		$hasher = $this -> mock ( Hasher::class ) ;
		$usersHandler = $this -> mock ( UsersHandler::class ) ;

		$facebookAccountsHandler = new FacebookAccountsHandler ( $authSessionsHandler , $carbon , $facebookAccountsRepo , $hasher , $usersHandler ) ;

		$facebookAccountsRepo -> shouldReceive ( 'getByKey' )
			-> withArgs ( [
				'key' ,
			] )
			-> andThrow ( RecordNotFoundException::class ) ;

		$carbon -> shouldReceive ( 'now' )
			-> andReturn ( 149 ) ;

		$hasher -> shouldReceive ( 'make' )
			-> withArgs ( [
				149 ,
			] )
			-> andReturn ( 150 ) ;

		$user = $this -> mock ( User::class ) ;
		$user -> id = 151 ;

		$usersHandler -> shouldReceive ( 'create' )
			-> andReturn ( $user ) ;

		$facebookAccount = $this -> mock ( FacebookAccount::class ) ;
		$facebookAccount -> user_id = 151 ;

		$facebookAccountsRepo -> shouldReceive ( 'create' )
			-> withArgs ( [
				151 ,
				'key' ,
			] )
			-> andReturn ( $facebookAccount ) ;

		$usersHandler -> shouldReceive ( 'get' )
			-> withArgs ( [
				151 ,
			] )
			-> andReturn ( $user ) ;

		$authSession = $this -> mock ( AuthSession::class ) ;

		$authSessionsHandler -> shouldReceive ( 'createForUserObject' )
			-> withArgs ( [
				$user ,
			] )
			-> andReturn ( $authSession ) ;

		$response = $facebookAccountsHandler -> getAuthSession ( 'key' , 'firstname' ) ;

		$this -> assertSame ( $authSession , $response ) ;
	}

}
