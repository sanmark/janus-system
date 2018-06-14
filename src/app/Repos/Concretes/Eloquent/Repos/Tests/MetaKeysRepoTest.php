<?php

namespace App\Repos\Concretes\Eloquent\Repos\Tests ;

use App\Models\MetaKey ;
use App\Models\User ;
use App\Repos\Concretes\Eloquent\Models\Meta as EMeta ;
use App\Repos\Concretes\Eloquent\Models\MetaKey as EMetaKey ;
use App\Repos\Concretes\Eloquent\Models\User as EUser ;
use App\Repos\Concretes\Eloquent\Repos\MetaKeysRepo ;
use App\Repos\Exceptions\RecordNotFoundException ;
use Illuminate\Database\Eloquent\ModelNotFoundException ;
use Tests\TestCase ;

/**
 * @codeCoverageIgnore
 */
class MetaKeysRepoTest extends TestCase
{

	public function test_all_ok ()
	{
		$eMeta = $this -> mock ( EMeta::class ) ;
		$eMetaKey = $this -> mock ( EMetaKey::class ) ;

		$metaKeysRepo = new MetaKeysRepo ( $eMeta , $eMetaKey ) ;

		$eMetaKey -> shouldReceive ( 'all' )
			-> withArgs ( [] )
			-> andReturn ( [
				$eMeta ,
			] ) ;

		$eMeta -> shouldReceive ( 'getAttribute' )
			-> withArgs ( [
				'id' ,
			] )
			-> andReturn ( 'the-id' ) ;

		$eMeta -> shouldReceive ( 'getAttribute' )
			-> withArgs ( [
				'key' ,
			] )
			-> andReturn ( 'the-key' ) ;

		$response = $metaKeysRepo -> all () ;

		$expectedMetaKey = new MetaKey() ;
		$expectedMetaKey -> id = 'the-id' ;
		$expectedMetaKey -> key = 'the-key' ;

		$this -> assertEquals ( [
			$expectedMetaKey ,
			] , $response ) ;
	}

	public function test_getByKey_ok ()
	{
		$eMeta = $this -> mock ( EMeta::class ) ;
		$eMetaKey = $this -> mock ( EMetaKey::class ) ;

		$metaKeysRepo = new MetaKeysRepo ( $eMeta , $eMetaKey ) ;

		$eMetaKey -> shouldReceive ( 'where' )
			-> withArgs ( [
				'key' ,
				'=' ,
				'the-key' ,
			] )
			-> andReturnSelf () ;

		$eMetaKey -> shouldReceive ( 'firstOrFail' )
			-> withArgs ( [] )
			-> andReturnSelf () ;

		$eMetaKey -> shouldReceive ( 'getAttribute' )
			-> withArgs ( [
				'id'
			] )
			-> andReturn ( 'the-id' ) ;

		$eMetaKey -> shouldReceive ( 'getAttribute' )
			-> withArgs ( [
				'key'
			] )
			-> andReturn ( 'the-key' ) ;

		$eMetaKey -> shouldReceive ( 'getAttribute' )
			-> withArgs ( [
				'created_at'
			] )
			-> andReturn ( 'the-created_at' ) ;

		$eMetaKey -> shouldReceive ( 'getAttribute' )
			-> withArgs ( [
				'updated_at'
			] )
			-> andReturn ( 'the-updated_at' ) ;

		$response = $metaKeysRepo -> getByKey ( 'the-key' ) ;

		$expectedMetaKey = new MetaKey() ;
		$expectedMetaKey -> id = 'the-id' ;
		$expectedMetaKey -> key = 'the-key' ;
		$expectedMetaKey -> created_at = 'the-created_at' ;
		$expectedMetaKey -> updated_at = 'the-updated_at' ;

		$this -> assertEquals ( $expectedMetaKey , $response ) ;
	}

	public function test_getByKey_throwsRecordNotFoundException ()
	{
		$eMeta = $this -> mock ( EMeta::class ) ;
		$eMetaKey = $this -> mock ( EMetaKey::class ) ;

		$metaKeysRepo = new MetaKeysRepo ( $eMeta , $eMetaKey ) ;

		$eMetaKey -> shouldReceive ( 'where' )
			-> withArgs ( [
				'key' ,
				'=' ,
				'the-key' ,
			] )
			-> andReturnSelf () ;

		$eMetaKey -> shouldReceive ( 'firstOrFail' )
			-> withArgs ( [] )
			-> andThrow ( ModelNotFoundException::class ) ;

		$this -> expectException ( RecordNotFoundException::class ) ;

		$metaKeysRepo -> getByKey ( 'the-key' ) ;
	}

	public function test_getUsersForMetaValue_ok ()
	{
		$eMeta = $this -> mock ( EMeta::class ) ;
		$eMetaKey = $this -> mock ( EMetaKey::class ) ;

		$metaKeysRepo = new MetaKeysRepo ( $eMeta , $eMetaKey ) ;

		$eMetaKey -> shouldReceive ( 'whereHas' )
			-> andReturnSelf () ;

		$eMetaKey -> shouldReceive ( 'where' )
			-> withArgs ( [
				'key' ,
				'=' ,
				'the-meta-key' ,
			] )
			-> andReturnSelf () ;

		$eMetaKey -> shouldReceive ( 'get' )
			-> withArgs ( [] )
			-> andReturn ( [
				$eMetaKey ,
			] ) ;

		$eMetaKey -> shouldReceive ( 'getAttribute' )
			-> withArgs ( [
				'metas' ,
			] )
			-> andReturn ( [
				$eMeta ,
			] ) ;

		$eUser = $this -> mock ( EUser::class ) ;

		$eMeta -> shouldReceive ( 'getAttribute' )
			-> withArgs ( [
				'user' ,
			] )
			-> andReturn ( $eUser ) ;

		$eUser -> shouldReceive ( 'getAttribute' )
			-> withArgs ( [
				'id' ,
			] )
			-> andReturn ( 'the-id' ) ;

		$eUser -> shouldReceive ( 'getAttribute' )
			-> withArgs ( [
				'key' ,
			] )
			-> andReturn ( 'the-key' ) ;

		$response = $metaKeysRepo -> getUsersForMetaValue ( 'the-meta-key' , 'the-meta-value' ) ;

		$expectedUser = new User() ;
		$expectedUser -> id = 'the-id' ;
		$expectedUser -> key = 'the-key' ;

		$this -> assertEquals ( [
			$expectedUser ,
			] , $response ) ;
	}

}
