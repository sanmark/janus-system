<?php

namespace App\Repos\Concretes\Eloquent\Repos ;

use App\Models\Meta ;
use App\Models\MetaKey ;
use App\Repos\Concretes\Eloquent\Models\Meta as eMeta ;
use App\Repos\Concretes\Eloquent\Models\MetaKey as eMetaKey ;
use App\Repos\Contracts\IMetaKeysRepo ;

class MetaKeysRepo implements IMetaKeysRepo
{

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

	public function all ()
	{
		$eModels = eMetaKey::all () ;
		$metakeys = [] ;
		foreach ( $eModels as $eModel )
		{
			$model = new MetaKey() ;
			$model -> id = $eModel -> id ;
			$model -> key = $eModel -> key ;
			array_push ( $metakeys , $model ) ;
		}
		return $metakeys ;
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

	public function getOneMetaForUser ( $userID , $metaKey )
	{
		$eModel = eMeta::where ( 'user_id' , $userID )
			-> whereHas ( 'metaKey' , function($q) use($metaKey)
			{
				$q -> where ( 'key' , $metaKey ) ;
			} )
			-> first () ;
		if ( ! $eModel )
		{
			return null ;
		}
		$model = new Meta() ;
		$model -> id = $eModel -> id ;
		$model -> meta_key_id = $eModel -> meta_key_id ;
		$model -> user_id = $eModel -> user_id ;
		$model -> value = $eModel -> value ;
		return $model ;
	}

	public function saveMetas ( $userID , array $data )
	{
		foreach ( $data as $key => $value )
		{
			$metaKey = eMetaKey::where ( 'key' , $key ) -> first () ;
			$eModel = eMeta::updateOrCreate ( [
					'user_id' => $userID ,
					'meta_key_id' => $metaKey -> id
					] , [
					'value' => $value
				] ) ;
		}
	}

}
