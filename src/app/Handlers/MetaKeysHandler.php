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

	public function getAllmetas ( $sessionKey )
	{
		$authSession = $this -> authSessionHandler -> getByKeyIfActiveAndExtendActiveTime ( $sessionKey ) ;
		$metas = $this -> metaKeysRepo -> getMetasForUser ( $authSession -> user_id ) ;
		$data = [] ;
		foreach ( $metas as $meta )
		{
			$data[] = [
				'meta_key' => $meta -> getMetaKey () ,
				'value' => $meta -> value ,
				'user_id' => $meta -> user_id
				] ;
		}
		return $data ;
	}

	public function getByKey ( string $key ): MetaKey
	{
		$metaKey = $this
			-> metaKeysRepo
			-> getByKey ( $key ) ;

		return $metaKey ;
	}

	public function getMetasForUser ( $userID )
	{
		$metas = $this -> metaKeysRepo -> getMetasForUser ( $userID ) ;
		$data = [] ;
		foreach ( $metas as $meta )
		{
			$data[] = [
				'meta_key' => $meta -> getMetaKey () ,
				'value' => $meta -> value ,
				'user_id' => $meta -> user_id
				] ;
		}
		return $data ;
	}

	public function getUsersForMetaValue ( string $metaKey , string $metaValue ): array
	{
		$users = $this
			-> metaKeysRepo
			-> getUsersForMetaValue ( $metaKey , $metaValue ) ;

		return $users ;
	}

}
