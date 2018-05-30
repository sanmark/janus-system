<?php

namespace App\Repos\Concretes\Eloquent\Repos ;

use App\Models\Meta ;
use App\Models\MetaKey ;
use App\Models\User ;
use App\Repos\Concretes\Eloquent\Models\Meta as eMeta ;
use App\Repos\Concretes\Eloquent\Models\MetaKey as eMetaKey ;
use App\Repos\Contracts\IMetaKeysRepo ;
use App\Repos\Exceptions\RecordNotFoundException ;
use Illuminate\Database\Eloquent\Builder ;
use Illuminate\Database\Eloquent\ModelNotFoundException ;

class MetaKeysRepo implements IMetaKeysRepo
{

	private $model ;
	private $eMeta ;
	private $eMetaKey ;

	public function __construct (
	eMeta $eMeta
	, eMetaKey $eMetaKey
	)
	{
		$this -> eMeta = $eMeta ;
		$this -> eMetaKey = $eMetaKey ;
		$this -> model = $eMetaKey ;
	}

	public function find ( $id )
	{
		$eModel = eMetaKey::find ( $id ) ;
		if ( ! $eModel )
		{
			return null ;
		}
		$model = new MetaKey() ;
		$model -> id = $eModel -> id ;
		$model -> key = $eModel -> key ;

		return $model ;
	}

	public function all (): array
	{
		$eMetaKeys = $this
			-> model
			-> all () ;

		$metaKeys = [] ;

		foreach ( $eMetaKeys as $eMetaKey )
		{
			$metaKey = new MetaKey() ;

			$metaKey -> id = $eMetaKey -> id ;
			$metaKey -> key = $eMetaKey -> key ;

			$metaKeys[] = $metaKey ;
		}

		return $metaKeys ;
	}

	public function getByKey ( string $key ): MetaKey
	{
		try
		{
			$eMetaKey = $this
				-> model
				-> where ( 'key' , '=' , $key )
				-> firstOrFail () ;

			$metaKey = new MetaKey() ;

			$metaKey -> id = $eMetaKey -> id ;
			$metaKey -> key = $eMetaKey -> key ;
			$metaKey -> created_at = $eMetaKey -> created_at ;
			$metaKey -> updated_at = $eMetaKey -> updated_at ;

			return $metaKey ;
		} catch ( ModelNotFoundException $ex )
		{
			throw new RecordNotFoundException() ;
		}
	}

	public function getMetasForUser ( $userID )
	{
		$eModels = eMeta::where ( 'user_id' , $userID )
			-> get () ;

		$metas = [] ;
		foreach ( $eModels as $eModel )
		{
			$model = new Meta() ;
			$model -> id = $eModel -> id ;
			$model -> meta_key_id = $eModel -> meta_key_id ;
			$model -> user_id = $eModel -> user_id ;
			$model -> value = $eModel -> value ;
			array_push ( $metas , $model ) ;
		}
		return $metas ;
	}

	public function getUsersForMetaValue ( string $metaKeyKey , string $metaValue ): array
	{
		$metaKeys = $this
			-> eMetaKey
			-> whereHas ( 'metas' , function (Builder $q) use ($metaValue)
			{
				$q -> where ( 'value' , '=' , $metaValue ) ;
			} )
			-> where ( 'key' , '=' , $metaKeyKey )
			-> get () ;

		$eUsers = [] ;

		foreach ( $metaKeys as $metaKey )
		{
			$metas = $metaKey -> metas ;

			foreach ( $metas as $meta )
			{
				$eUsers[] = $meta -> user ;
			}
		}

		$users = [] ;

		foreach ( $eUsers as $eUser )
		{
			$user = new User() ;

			$user -> id = $eUser -> id ;
			$user -> key = $eUser -> key ;

			$users[] = $user ;
		}

		return $users ;
	}

}
