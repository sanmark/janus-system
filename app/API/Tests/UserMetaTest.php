<?php

namespace App\API\Tests ;

use App\Repos\Concretes\Eloquent\Models\MetaKey ;
use Illuminate\Foundation\Testing\DatabaseMigrations ;
use Tests\TestCase ;
use function factory ;

class UserMetaTest extends TestCase
{

	use DatabaseMigrations ;

	public function testGetAllMetakeys ()
	{
		factory ( MetaKey::class ) -> create ( [
			'id' => 1 ,
			'key' => 'key1'
		] ) ;
		factory ( MetaKey::class ) -> create ( [
			'id' => 2 ,
			'key' => 'key2'
		] ) ;

		$this -> post ( "api/metakeys" )
			-> assertStatus ( 200 )
			-> assertjson ( [
				"data" => [
					[
						'id' => 1 ,
						'key' => 'key1'
					] ,
					[
						'id' => 2 ,
						'key' => 'key2'
					]
				]
			] ) ;
	}

	public function testGetAllMetaKeysWhenNoKey ()
	{
		$this -> post ( "api/metakeys" )
			-> assertStatus ( 200 )
			-> assertjson ( [
				"data" => []
			] ) ;
	}

}
