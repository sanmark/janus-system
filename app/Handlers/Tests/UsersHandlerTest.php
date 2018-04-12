<?php

namespace App\Handlers\Tests ;

use App\Handlers\UsersHandler ;
use App\Helpers\ArrayHelper ;
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

	public function testCreate_Ok ()
	{
		$mockArrayHelper = Mockery::mock ( ArrayHelper::class ) ;
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

		$usersHandler = new UsersHandler ( $mockArrayHelper , $mockHash , $mockIUsersRepo ) ;

		$response = $usersHandler -> create ( $userKey , $userSecret ) ;

		$this -> assertSame ( $mockUserModel , $response ) ;
	}

	public function testGetUserIfCredentialsValid_Ok ()
	{
		$mockArrayHelper = Mockery::mock ( ArrayHelper::class ) ;
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

		$usersHandler = new UsersHandler ( $mockArrayHelper , $mockHash , $mockIUsersRepo ) ;

		$response = $usersHandler -> getUserIfCredentialsValid ( 'the_key' , 'the_secret' ) ;

		$this -> assertSame ( $mockUser , $response ) ;
	}

	public function testGetUserIfCredentialsValid_HandlesInvalidCredentials ()
	{

		$this -> expectException ( RecordNotFoundException::class ) ;

		$mockArrayHelper = $this -> mock ( ArrayHelper::class ) ;
		$mockHash = $this -> mock ( Hasher::class ) ;
		$mockIUsersRepo = $this -> mock ( IUsersRepo::class ) ;

		$usersHandler = new UsersHandler ( $mockArrayHelper , $mockHash , $mockIUsersRepo ) ;

		$user = $this -> mock ( User::class ) ;

		$user -> secret = 'the_secret_saved_in_db' ;

		$mockIUsersRepo
			-> shouldReceive ( 'getByKey' )
			-> withArgs ( [
				'the_key' ,
			] )
			-> andReturn ( $user ) ;

		$mockHash
			-> shouldReceive ( 'check' )
			-> withArgs ( [
				'the_secret' ,
				'the_secret_saved_in_db' ,
			] )
			-> andReturn ( FALSE ) ;

		$usersHandler -> getUserIfCredentialsValid ( 'the_key' , 'the_secret' ) ;
	}

}
