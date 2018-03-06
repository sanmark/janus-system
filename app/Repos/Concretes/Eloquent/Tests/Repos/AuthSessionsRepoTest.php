<?php

namespace App\Repos\Concretes\Eloquent\Tests\Repos ;

use App\Models\AuthSession ;
use App\Models\User ;
use App\Repos\Concretes\Eloquent\Models\AuthSession as eAuthSession ;
use App\Repos\Concretes\Eloquent\Repos\AuthSessionsRepo ;
use App\Repos\Concretes\Eloquent\Repos\UsersRepo ;
use Illuminate\Contracts\Hashing\Hasher ;
use Tests\TestCase ;

/**
 * @codeCoverageIgnore
 */
class AuthSessionsRepoTest extends TestCase
{

	public function testCreateOk ()
	{
		$hash = $this -> mock ( Hasher::class ) ;
		$eAuthSession = $this -> mock ( eAuthSession::class ) ;
		$usersRepo = $this -> mock ( UsersRepo::class ) ;

		$authSessionRepo = new AuthSessionsRepo (
			$hash
			, $eAuthSession
			, $usersRepo
			) ;

		$eAuthSession
			-> shouldReceive ( 'newInstance' )
			-> andReturn ( $eAuthSession ) ;

		$user = $this -> mock ( User::class ) ;

		$user -> id = 150 ;

		$usersRepo
			-> shouldReceive ( 'get' )
			-> withArgs ( [
				149 ,
			] )
			-> andReturn ( $user ) ;

		$eAuthSession
			-> shouldReceive ( 'setAttribute' )
			-> withArgs ( [
				'key' ,
				'the_hashed' ,
			] )
			-> andReturns () ;

		$eAuthSession
			-> shouldReceive ( 'setAttribute' )
			-> withArgs ( [
				'user_id' ,
				150 ,
			] )
			-> andReturns () ;

		$hash
			-> shouldReceive ( 'make' )
			-> andReturn ( 'the_hashed' ) ;

		$eAuthSession
			-> shouldReceive ( 'save' ) ;

		$eAuthSession
			-> shouldReceive ( 'getAttribute' )
			-> withArgs ( [
				'id' ,
			] )
			-> andReturn ( 'the_id' ) ;

		$eAuthSession
			-> shouldReceive ( 'getAttribute' )
			-> withArgs ( [
				'key' ,
			] )
			-> andReturn ( 'the_key' ) ;

		$result = $authSessionRepo -> create ( 149 ) ;

		$this -> assertInstanceOf ( AuthSession::class , $result ) ;
		$this -> assertSame ( 'the_id' , $result -> id ) ;
		$this -> assertSame ( 'the_key' , $result -> key ) ;
		$this -> assertSame ( 150 , $result -> user_id ) ;
	}

}
