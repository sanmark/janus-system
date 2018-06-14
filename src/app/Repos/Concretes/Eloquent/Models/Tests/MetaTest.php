<?php

namespace App\Repos\Concretes\Eloquent\Models\Tests ;

use App\Repos\Concretes\Eloquent\Models\Meta ;
use App\Repos\Concretes\Eloquent\Models\MetaKey ;
use App\Repos\Concretes\Eloquent\Models\User ;
use Tests\TestCase ;

/**
 * @codeCoverageIgnore
 */
class MetaTest extends TestCase
{

	public function test_metaKey_ok ()
	{
		$meta = $this -> mock ( Meta::class . '[belongsTo]' ) ;

		$meta -> shouldReceive ( 'belongsTo' )
			-> withArgs ( [
				MetaKey::class ,
			] )
			-> andReturn ( 149 ) ;

		$response = $meta -> metaKey () ;

		$this -> assertEquals ( 149 , $response ) ;
	}

	public function test_user_ok ()
	{
		$meta = $this -> mock ( Meta::class . '[belongsTo]' ) ;

		$meta -> shouldReceive ( 'belongsTo' )
			-> withArgs ( [
				User::class ,
			] )
			-> andReturn ( 149 ) ;

		$response = $meta -> user () ;

		$this -> assertEquals ( 149 , $response ) ;
	}

}
