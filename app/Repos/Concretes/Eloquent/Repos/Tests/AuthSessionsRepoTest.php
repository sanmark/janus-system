<?php

namespace App\Repos\Concretes\Eloquent\Repos\Tests ;

use App\Models\AuthSession ;
use App\Models\User ;
use App\Repos\Concretes\Eloquent\Models\AuthSession as eAuthSession ;
use App\Repos\Concretes\Eloquent\Repos\AuthSessionsRepo ;
use App\Repos\Concretes\Eloquent\Repos\UsersRepo ;
use App\Repos\Exceptions\RecordNotFoundException ;
use Illuminate\Contracts\Hashing\Hasher ;
use Illuminate\Database\Eloquent\ModelNotFoundException ;
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
				149 ,
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
		$this -> assertSame ( 149 , $result -> user_id ) ;
	}

	public function testGetByKeyOk ()
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
			-> shouldReceive ( 'where' )
			-> withArgs ( [
				'key' ,
				'=' ,
				'rofl'
			] )
			-> andReturnSelf () ;

		$eAuthSession
			-> shouldReceive ( 'firstOrFail' )
			-> andReturnSelf () ;

		$attributes = [
			'id' => 'the_id' ,
			'key' => 'the_key' ,
			'user_id' => 'the_user_id' ,
			'created_at' => 'the_created_at' ,
			'updated_at' => 'the_updated_at' ,
			] ;

		foreach ( $attributes as $key => $value )
		{
			$eAuthSession
				-> shouldReceive ( 'getAttribute' )
				-> withArgs ( [
					$key ,
				] )
				-> andReturn ( $value ) ;
		}

		$result = $authSessionRepo -> getByKey ( 'rofl' ) ;

		$this -> assertInstanceOf ( AuthSession::class , $result ) ;
		foreach ( $attributes as $key => $value )
		{
			$this -> assertSame ( $value , $result -> {$key} ) ;
		}
	}

	public function testGetByKeyThrowsRecordNotFoundException ()
	{
		$this -> expectException ( RecordNotFoundException::class ) ;

		$hash = $this -> mock ( Hasher::class ) ;
		$eAuthSession = $this -> mock ( eAuthSession::class ) ;
		$usersRepo = $this -> mock ( UsersRepo::class ) ;

		$authSessionRepo = new AuthSessionsRepo (
			$hash
			, $eAuthSession
			, $usersRepo
			) ;

		$eAuthSession
			-> shouldReceive ( 'where' )
			-> withArgs ( [
				'key' ,
				'=' ,
				'rofl'
			] )
			-> andReturnSelf () ;

		$eAuthSession
			-> shouldReceive ( 'firstOrFail' )
			-> andThrow ( ModelNotFoundException::class ) ;

		$authSessionRepo -> getByKey ( 'rofl' ) ;
	}

	public function test_update_ok ()
	{
		$hash = $this -> mock ( Hasher::class ) ;
		$eAuthSession = $this -> mock ( eAuthSession::class ) ;
		$usersRepo = $this -> mock ( UsersRepo::class ) ;

		$authSessionRepo = new AuthSessionsRepo (
			$hash
			, $eAuthSession
			, $usersRepo
			) ;

		$authSessionRepo = $this -> mock ( AuthSessionsRepo::class . '[getByKey]' , [
			$hash ,
			$eAuthSession ,
			$usersRepo ,
			] ) ;

		$authSession = $this -> mock ( AuthSession::class ) ;

		$eAuthSession
			-> shouldReceive ( 'findOrFail' )
			-> withArgs ( [
				149 ,
			] )
			-> andReturnSelf () ;

		$authSession -> id = 149 ;
		$authSession -> updated_at = 'the_updated_at' ;

		$eAuthSession
			-> shouldReceive ( 'setAttribute' )
			-> withArgs ( [
				'updated_at' ,
				'the_updated_at' ,
			] )
			-> andReturns () ;

		$eAuthSession
			-> shouldReceive ( 'save' )
			-> andReturns () ;

		$eAuthSession
			-> shouldReceive ( 'getAttribute' )
			-> withArgs ( [
				'key'
			] )
			-> andReturn ( 'the_key' ) ;

		$authSessionRepo
			-> shouldReceive ( 'getByKey' )
			-> withArgs ( [
				'the_key' ,
			] )
			-> andReturn ( $authSession ) ;

		$result = $authSessionRepo -> update ( $authSession ) ;

		$this -> assertSame ( $authSession , $result ) ;
	}

}
