<?php

namespace App\Handlers ;

use App\API\Validators\Contracts\IMetasValidator ;
use App\Models\MetaKey ;
use App\Repos\Contracts\IMetaKeysRepo ;

class MetaKeysHandler
{

	private $authSessionHandler ;
	private $metasValidator ;
	private $metaKeysRepo ;

	public function __construct (
	AuthSessionsHandler $authSessionsHandler
	, IMetasValidator $metasValidator
	, IMetaKeysRepo $metaKeysRepo
	)
	{
		$this -> authSessionHandler = $authSessionsHandler ;
		$this -> metasValidator = $metasValidator ;
		$this -> metaKeysRepo = $metaKeysRepo ;
	}

	public function all ()
	{
		return $this -> metaKeysRepo -> all () ;
	}

	public function getByKey ( string $key ): MetaKey
	{
		$metaKey = $this
			-> metaKeysRepo
			-> getByKey ( $key ) ;

		return $metaKey ;
	}

	public function getUsersForMetaValue ( string $metaKey , string $metaValue ): array
	{
		$users = $this
			-> metaKeysRepo
			-> getUsersForMetaValue ( $metaKey , $metaValue ) ;

		return $users ;
	}

}
