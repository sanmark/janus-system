<?php

namespace App\Handlers\Tests ;

use App\API\Validators\Contracts\IMetasValidator ;
use App\Handlers\AuthSessionsHandler ;
use App\Handlers\MetaKeysHandler ;
use App\Models\MetaKey ;
use App\Repos\Contracts\IMetaKeysRepo ;
use Tests\TestCase ;

class MetaKeysHandlerTest extends TestCase
{

	public function test_all_ok ()
	{
		$authSessionsHandler = $this -> mock ( AuthSessionsHandler::class ) ;
		$metasValidator = $this -> mock ( IMetasValidator::class ) ;
		$metaKeysRepo = $this -> mock ( IMetaKeysRepo::class ) ;

		$metaKeysHandler = new MetaKeysHandler ( $authSessionsHandler , $metasValidator , $metaKeysRepo ) ;

		$metaKeysRepo -> shouldReceive ( 'all' )
			-> withArgs ( [] )
			-> andReturn ( [
				149 ,
			] ) ;

		$response = $metaKeysHandler -> all () ;

		$this -> assertSame ( [ 149 , ] , $response ) ;
	}

	public function test_getByKey_ok ()
	{
		$authSessionsHandler = $this -> mock ( AuthSessionsHandler::class ) ;
		$metasValidator = $this -> mock ( IMetasValidator::class ) ;
		$metaKeysRepo = $this -> mock ( IMetaKeysRepo::class ) ;

		$metaKeysHandler = new MetaKeysHandler ( $authSessionsHandler , $metasValidator , $metaKeysRepo ) ;

		$metaKey = $this -> mock ( MetaKey::class ) ;

		$metaKeysRepo -> shouldReceive ( 'getByKey' )
			-> withArgs ( [
				'the-key' ,
			] )
			-> andReturn ( $metaKey ) ;

		$response = $metaKeysHandler -> getByKey ( 'the-key' ) ;

		$this -> assertSame ( $metaKey , $response ) ;
	}

	public function test_getUsersForMetaValue_ok ()
	{
		$authSessionsHandler = $this -> mock ( AuthSessionsHandler::class ) ;
		$metasValidator = $this -> mock ( IMetasValidator::class ) ;
		$metaKeysRepo = $this -> mock ( IMetaKeysRepo::class ) ;

		$metaKeysHandler = new MetaKeysHandler ( $authSessionsHandler , $metasValidator , $metaKeysRepo ) ;

		$metaKeysRepo -> shouldReceive ( 'getUsersForMetaValue' )
			-> withArgs ( [
				'the-meta-key' ,
				'the-meta-value' ,
			] )
			-> andReturn ( [
				149 ,
			] ) ;

		$response = $metaKeysHandler -> getUsersForMetaValue ( 'the-meta-key' , 'the-meta-value' ) ;

		$this -> assertSame ( [
			149 ,
			] , $response ) ;
	}

}
