<?php

namespace App\Handlers ;

use App\API\Validators\Contracts\IMetasValidator ;
use App\Repos\Contracts\IMetaKeysRepo ;
use function app ;

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

	public function getMetaForKey ( $sessionKey , $key )
	{
		$authSession = $this -> authSessionHandler -> getByKeyIfActiveAndExtendActiveTime ( $sessionKey ) ;
		$meta = $this -> metaKeysRepo -> getOneMetaForUser ( $authSession -> user_id , $key ) ;
		if ( $meta == null )
		{
			return null ;
		}
		$data = [] ;
		$data[ "meta_key" ] = $meta -> getMetaKey () ;
		$data[ "value" ] = $meta -> value ;
		$data[ "user_id" ] = $meta -> user_id ;
		return $data ;
	}

	public function saveMetas ( $sessionKey , array $data )
	{
		$authSession = $this -> authSessionHandler -> getByKeyIfActiveAndExtendActiveTime ( $sessionKey ) ;
		$this -> metasValidator -> saveMetas ( $data ) ;

		$this -> metaKeysRepo -> saveMetas ( $authSession -> user_id , $data ) ;
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

	public function getMetaForUser ( $userID , $key )
	{
		$meta = $this -> metaKeysRepo -> getOneMetaForUser ( $userID , $key ) ;
		if ( $meta == null )
		{
			return null ;
		}
		$data = [] ;
		$data[ "meta_key" ] = $meta -> getMetaKey () ;
		$data[ "value" ] = $meta -> value ;
		$data[ "user_id" ] = $meta -> user_id ;
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
