<?php

namespace App\Handlers\Tests ;

use App\Handlers\UsersHandler ;
use App\Models\User ;
use App\Repos\Contracts\IUsersRepo ;
use App\Repos\Exceptions\RecordNotFoundException ;
use Illuminate\Contracts\Hashing\Hasher ;
use Mockery ;
use Tests\TestCase ;

/**
 * @codeCoverageIgnore
 */
class UsersHandlerTest extends TestCase
{

	public function testCreateOk ()
	{
		$mockHash = Mockery::mock ( Hasher::class ) ;
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

		$usersHandler = new UsersHandler ( $mockHash , $mockIUsersRepo ) ;

		$response = $usersHandler -> create ( $userKey , $userSecret ) ;

		$this -> assertSame ( $mockUserModel , $response ) ;
	}

	public function testGetUserIfCredentialsValidOk ()
	{
		$mockIUsersRepo = Mockery::mock ( IUsersRepo::class ) ;
		$mockHash = Mockery::mock ( Hasher::class ) ;
		$mockUser = Mockery::mock ( User::class ) ;

		$mockIUsersRepo
			-> shouldReceive ( 'getByKey' )
			-> withArgs ( [
				'the_key' ,
			] )
			-> andReturn ( $mockUser ) ;

		$mockHash
			-> shouldReceive ( 'check' )
			-> withArgs ( [
				'the_secret' ,
				'asdf' ,
			] )
			-> andReturn ( TRUE ) ;

		$mockUser -> secret = 'asdf' ;

		$usersHandler = new UsersHandler ( $mockHash , $mockIUsersRepo ) ;

		$response = $usersHandler -> getUserIfCredentialsValid ( 'the_key' , 'the_secret' ) ;

		$this -> assertSame ( $mockUser , $response ) ;
	}

	public function testGetUserIfCredentialsValidHandlesInvalidCredentials ()
	{
		$this -> expectException ( RecordNotFoundException::class ) ;

		$hash = $this -> mock ( Hasher::class ) ;
		$iUsersRepo = $this -> mock ( IUsersRepo::class ) ;

		$usersHandler = new UsersHandler ( $hash , $iUsersRepo ) ;

		$user = $this -> mock ( User::class ) ;

		$user -> secret = 'the_secret_saved_in_db' ;

		$iUsersRepo
			-> shouldReceive ( 'getByKey' )
			-> withArgs ( [
				'the_key' ,
			] )
			-> andReturn ( $user ) ;

		$hash
			-> shouldReceive ( 'check' )
			-> withArgs ( [
				'the_secret' ,
				'the_secret_saved_in_db' ,
			] )
			-> andReturn ( FALSE ) ;

		$usersHandler -> getUserIfCredentialsValid ( 'the_key' , 'the_secret' ) ;
	}

}
