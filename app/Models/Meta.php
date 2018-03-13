<?php

namespace App\Models ;

use App\Repos\Contracts\IMetaKeysRepo ;
use function app ;

class Meta
{

	private $metaKeyRepo ;
	public $id ;
	public $meta_key_id ;
	public $user_id ;
	public $value ;

	public function __construct ()
	{
		$this -> metaKeyRepo = app ( IMetaKeysRepo::class ) ;
	}

	public function getMetaKey ()
	{
		$metaKey = $this -> metaKeyRepo -> find ( $this -> meta_key_id ) ;
		if ( ! $metaKey )
		{
			return null ;
		}
		return $metaKey -> key ;
	}

}
