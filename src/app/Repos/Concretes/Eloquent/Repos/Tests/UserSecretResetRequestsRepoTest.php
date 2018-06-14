<?php

namespace App\Repos\Concretes\Eloquent\Repos\Tests ;

use App\Models\UserSecretResetRequest ;
use App\Repos\Concretes\Eloquent\Models\UserSecretResetRequest as EUserSecretResetRequest ;
use App\Repos\Concretes\Eloquent\Repos\UserSecretResetRequestsRepo ;
use App\Repos\Exceptions\RecordNotFoundException ;
use Carbon\Carbon ;
use Illuminate\Contracts\Hashing\Hasher ;
use Illuminate\Database\Eloquent\ModelNotFoundException ;
use Tests\TestCase ;

/**
 * @codeCoverageIgnore
 */
class UserSecretResetRequestsRepoTest extends TestCase
{

	public function test_create_ok ()
	{
		$carbon = $this -> mock ( Carbon::class ) ;
		$eUserSecretResetRequest = $this -> mock ( EUserSecretResetRequest::class ) ;
		$hasher = $this -> mock ( Hasher::class ) ;

		$userSecretResetRequestRepo = new UserSecretResetRequestsRepo ( $carbon , $eUserSecretResetRequest , $hasher ) ;

		$eUserSecretResetRequest -> shouldReceive ( 'newInstance' )
			-> withArgs ( [] )
			-> andReturnSelf () ;

		$eUserSecretResetRequest -> shouldReceive ( 'setAttribute' )
			-> withArgs ( [
				'user_id' ,
				149 ,
			] ) ;

		$carbon -> shouldReceive ( 'now' )
			-> withArgs ( [] )
			-> andReturn ( 'the-now' ) ;

		$hasher -> shouldReceive ( 'make' )
			-> withArgs ( [
				'the-now' ,
			] )
			-> andReturn ( 'the-hash' ) ;

		$eUserSecretResetRequest -> shouldReceive ( 'setAttribute' )
			-> withArgs ( [
				'token' ,
				'the-hash' ,
			] ) ;

		$eUserSecretResetRequest -> shouldReceive ( 'save' )
			-> withArgs ( [] ) ;

		$eUserSecretResetRequest -> shouldReceive ( 'getAttribute' )
			-> withArgs ( [
				'id'
			] )
			-> andReturn ( 'the-id' ) ;

		$eUserSecretResetRequest -> shouldReceive ( 'getAttribute' )
			-> withArgs ( [
				'user_id'
			] )
			-> andReturn ( 'the-user_id' ) ;

		$eUserSecretResetRequest -> shouldReceive ( 'getAttribute' )
			-> withArgs ( [
				'token'
			] )
			-> andReturn ( 'the-token' ) ;

		$eUserSecretResetRequest -> shouldReceive ( 'getAttribute' )
			-> withArgs ( [
				'created_at'
			] )
			-> andReturn ( 'the-created_at' ) ;

		$eUserSecretResetRequest -> shouldReceive ( 'getAttribute' )
			-> withArgs ( [
				'updated_at'
			] )
			-> andReturn ( 'the-updated_at' ) ;

		$response = $userSecretResetRequestRepo -> create ( 149 ) ;

		$expectedUserSecretResetRequest = new UserSecretResetRequest() ;
		$expectedUserSecretResetRequest -> id = 'the-id' ;
		$expectedUserSecretResetRequest -> user_id = 'the-user_id' ;
		$expectedUserSecretResetRequest -> token = 'the-token' ;
		$expectedUserSecretResetRequest -> created_at = 'the-created_at' ;
		$expectedUserSecretResetRequest -> updated_at = 'the-updated_at' ;

		$this -> assertEquals ( $expectedUserSecretResetRequest , $response ) ;
	}

	public function test_deleteOfUser_ok ()
	{
		$carbon = $this -> mock ( Carbon::class ) ;
		$eUserSecretResetRequest = $this -> mock ( EUserSecretResetRequest::class ) ;
		$hasher = $this -> mock ( Hasher::class ) ;

		$userSecretResetRequestRepo = new UserSecretResetRequestsRepo ( $carbon , $eUserSecretResetRequest , $hasher ) ;

		$eUserSecretResetRequest -> shouldReceive ( 'where' )
			-> withArgs ( [
				'user_id' ,
				'=' ,
				149 ,
			] )
			-> andReturnSelf () ;

		$eUserSecretResetRequest -> shouldReceive ( 'get' )
			-> withArgs ( [] )
			-> andReturn ( [ $eUserSecretResetRequest ] ) ;

		$eUserSecretResetRequest -> shouldReceive ( 'delete' )
			-> withArgs ( [] ) ;

		$userSecretResetRequestRepo -> deleteOfUser ( 149 ) ;
	}

	public function test_getByToken_ok ()
	{
		$carbon = $this -> mock ( Carbon::class ) ;
		$eUserSecretResetRequest = $this -> mock ( EUserSecretResetRequest::class ) ;
		$hasher = $this -> mock ( Hasher::class ) ;

		$userSecretResetRequestRepo = new UserSecretResetRequestsRepo ( $carbon , $eUserSecretResetRequest , $hasher ) ;

		$eUserSecretResetRequest -> shouldReceive ( 'where' )
			-> withArgs ( [
				'token' ,
				'=' ,
				'the-token' ,
			] )
			-> andReturnSelf () ;

		$eUserSecretResetRequest -> shouldReceive ( 'firstOrFail' )
			-> withArgs ( [] )
			-> andReturnSelf () ;

		$eUserSecretResetRequest -> shouldReceive ( 'getAttribute' )
			-> withArgs ( [
				'id' ,
			] )
			-> andReturn ( 'the-id' ) ;

		$eUserSecretResetRequest -> shouldReceive ( 'getAttribute' )
			-> withArgs ( [
				'user_id' ,
			] )
			-> andReturn ( 'the-user_id' ) ;

		$eUserSecretResetRequest -> shouldReceive ( 'getAttribute' )
			-> withArgs ( [
				'token' ,
			] )
			-> andReturn ( 'the-token' ) ;

		$eUserSecretResetRequest -> shouldReceive ( 'getAttribute' )
			-> withArgs ( [
				'created_at' ,
			] )
			-> andReturn ( 'the-created_at' ) ;

		$eUserSecretResetRequest -> shouldReceive ( 'getAttribute' )
			-> withArgs ( [
				'updated_at' ,
			] )
			-> andReturn ( 'the-updated_at' ) ;

		$response = $userSecretResetRequestRepo -> getByToken ( 'the-token' ) ;

		$expectedUserSecretResetRequest = new UserSecretResetRequest() ;
		$expectedUserSecretResetRequest -> id = 'the-id' ;
		$expectedUserSecretResetRequest -> user_id = 'the-user_id' ;
		$expectedUserSecretResetRequest -> token = 'the-token' ;
		$expectedUserSecretResetRequest -> created_at = 'the-created_at' ;
		$expectedUserSecretResetRequest -> updated_at = 'the-updated_at' ;

		$this -> assertEquals ( $expectedUserSecretResetRequest , $response ) ;
	}

	public function test_getByToken_throwsRecordNotFoundException ()
	{
		$carbon = $this -> mock ( Carbon::class ) ;
		$eUserSecretResetRequest = $this -> mock ( EUserSecretResetRequest::class ) ;
		$hasher = $this -> mock ( Hasher::class ) ;

		$userSecretResetRequestRepo = new UserSecretResetRequestsRepo ( $carbon , $eUserSecretResetRequest , $hasher ) ;

		$eUserSecretResetRequest -> shouldReceive ( 'where' )
			-> withArgs ( [
				'token' ,
				'=' ,
				'the-token' ,
			] )
			-> andReturnSelf () ;

		$eUserSecretResetRequest -> shouldReceive ( 'firstOrFail' )
			-> withArgs ( [] )
			-> andThrow ( ModelNotFoundException::class ) ;

		$this -> expectException ( RecordNotFoundException::class ) ;

		$userSecretResetRequestRepo -> getByToken ( 'the-token' ) ;
	}

}
