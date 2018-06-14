<?php

namespace App\Handlers\Tests ;

use App\API\Validators\Contracts\IMetasValidator ;
use App\Handlers\AuthSessionsHandler ;
use App\Handlers\MetaKeysHandler ;
use App\Handlers\MetasHandler ;
use App\Handlers\UsersHandler ;
use App\Models\Meta ;
use App\Models\MetaKey ;
use App\Models\User ;
use App\Repos\Contracts\IMetasRepo ;
use App\Repos\Exceptions\RecordNotFoundException ;
use Tests\TestCase ;

class MetasHandlerTest extends TestCase
{

	public function test_getAllByUserId_ok ()
	{
		$authSessionsHandler = $this -> mock ( AuthSessionsHandler::class ) ;
		$metasRepo = $this -> mock ( IMetasRepo::class ) ;
		$metasValidator = $this -> mock ( IMetasValidator::class ) ;
		$metaKeysHandler = $this -> mock ( MetaKeysHandler::class ) ;
		$usersHandler = $this -> mock ( UsersHandler::class ) ;

		$metasHandler = new MetasHandler ( $authSessionsHandler , $metasRepo , $metasValidator , $metaKeysHandler , $usersHandler ) ;

		$user = $this -> mock ( User::class ) ;
		$user -> id = 149 ;

		$usersHandler -> shouldReceive ( 'get' )
			-> withArgs ( [
				149 ,
			] )
			-> andReturn ( $user ) ;

		$metasRepo -> shouldReceive ( 'getAllByUserId' )
			-> withArgs ( [
				149 ,
			] )
			-> andReturn ( [
				150 ,
			] ) ;

		$response = $metasHandler -> getAllByUserId ( 149 ) ;

		$this -> assertSame ( [
			150 ,
			] , $response ) ;
	}

	public function test_getOneByUserIdAndMetaKeyKey_ok ()
	{
		$authSessionsHandler = $this -> mock ( AuthSessionsHandler::class ) ;
		$metasRepo = $this -> mock ( IMetasRepo::class ) ;
		$metasValidator = $this -> mock ( IMetasValidator::class ) ;
		$metaKeysHandler = $this -> mock ( MetaKeysHandler::class ) ;
		$usersHandler = $this -> mock ( UsersHandler::class ) ;

		$metasHandler = new MetasHandler ( $authSessionsHandler , $metasRepo , $metasValidator , $metaKeysHandler , $usersHandler ) ;

		$meta = $this -> mock ( Meta::class ) ;

		$metasRepo -> shouldReceive ( 'getOneByUserIdAndMetaKey' )
			-> withArgs ( [
				149 ,
				'the-meta-key' ,
			] )
			-> andReturn ( $meta ) ;

		$response = $metasHandler -> getOneByUserIdAndMetaKeyKey ( 149 , 'the-meta-key' ) ;

		$this -> assertSame ( $meta , $response ) ;
	}

	public function test_createByUserIdAndMetaKeyKey_existingMeta ()
	{
		$authSessionsHandler = $this -> mock ( AuthSessionsHandler::class ) ;
		$metasRepo = $this -> mock ( IMetasRepo::class ) ;
		$metasValidator = $this -> mock ( IMetasValidator::class ) ;
		$metaKeysHandler = $this -> mock ( MetaKeysHandler::class ) ;
		$usersHandler = $this -> mock ( UsersHandler::class ) ;

		$metasHandler = $this -> mock ( MetasHandler::class . '[getOneByUserIdAndMetaKeyKey]' , [
			$authSessionsHandler ,
			$metasRepo ,
			$metasValidator ,
			$metaKeysHandler ,
			$usersHandler
			] ) ;

		$user = $this -> mock ( User::class ) ;

		$usersHandler -> shouldReceive ( 'get' )
			-> withArgs ( [
				149 ,
			] )
			-> andReturn ( $user ) ;

		$metaKey = $this -> mock ( MetaKey::class ) ;

		$metaKeysHandler -> shouldReceive ( 'getByKey' )
			-> withArgs ( [
				'the-meta-key' ,
			] )
			-> andReturn ( $metaKey ) ;

		$metasValidator -> shouldReceive ( 'createByUserIdAndMetaKey' )
			-> withArgs ( [
				[
					'value' => 'the-meta-value' ,
				] ,
			] ) ;

		$meta = $this -> mock ( Meta::class ) ;
		$meta -> id = 150 ;

		$metasHandler -> shouldReceive ( 'getOneByUserIdAndMetaKeyKey' )
			-> withArgs ( [
				149 ,
				'the-meta-key' ,
			] )
			-> andReturn ( $meta ) ;

		$metasRepo -> shouldReceive ( 'update' )
			-> withArgs ( [
				150 ,
				'the-meta-value' ,
			] )
			-> andReturn ( $meta ) ;

		$response = $metasHandler -> createByUserIdAndMetaKeyKey ( 149 , 'the-meta-key' , 'the-meta-value' ) ;

		$this -> assertSame ( $meta , $response ) ;
	}

	public function test_createByUserIdAndMetaKeyKey_newMeta ()
	{
		$authSessionsHandler = $this -> mock ( AuthSessionsHandler::class ) ;
		$metasRepo = $this -> mock ( IMetasRepo::class ) ;
		$metasValidator = $this -> mock ( IMetasValidator::class ) ;
		$metaKeysHandler = $this -> mock ( MetaKeysHandler::class ) ;
		$usersHandler = $this -> mock ( UsersHandler::class ) ;

		$metasHandler = $this -> mock ( MetasHandler::class . '[getOneByUserIdAndMetaKeyKey]' , [
			$authSessionsHandler ,
			$metasRepo ,
			$metasValidator ,
			$metaKeysHandler ,
			$usersHandler
			] ) ;

		$user = $this -> mock ( User::class ) ;
		$user -> id = 149 ;

		$usersHandler -> shouldReceive ( 'get' )
			-> withArgs ( [
				149 ,
			] )
			-> andReturn ( $user ) ;

		$metaKey = $this -> mock ( MetaKey::class ) ;
		$metaKey -> id = 150 ;

		$metaKeysHandler -> shouldReceive ( 'getByKey' )
			-> withArgs ( [
				'the-meta-key' ,
			] )
			-> andReturn ( $metaKey ) ;

		$metasValidator -> shouldReceive ( 'createByUserIdAndMetaKey' )
			-> withArgs ( [
				[
					'value' => 'the-meta-value' ,
				] ,
			] ) ;

		$metasHandler -> shouldReceive ( 'getOneByUserIdAndMetaKeyKey' )
			-> withArgs ( [
				149 ,
				'the-meta-key' ,
			] )
			-> andThrow ( RecordNotFoundException::class ) ;

		$meta = $this -> mock ( Meta::class ) ;

		$metasRepo -> shouldReceive ( 'create' )
			-> withArgs ( [
				149 ,
				150 ,
				'the-meta-value' ,
			] )
			-> andReturn ( $meta ) ;

		$response = $metasHandler -> createByUserIdAndMetaKeyKey ( 149 , 'the-meta-key' , 'the-meta-value' ) ;

		$this -> assertSame ( $meta , $response ) ;
	}

}
