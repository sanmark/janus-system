<?php

namespace App\Repos\Concretes\Eloquent\Repos ;

use App\Models\Meta ;
use App\Repos\Concretes\Eloquent\Models\Meta as eMeta ;
use App\Repos\Contracts\IMetasRepo ;
use Illuminate\Database\Eloquent\Builder ;

class MetasRepo implements IMetasRepo
{

	private $model ;

	public function __construct ( eMeta $eMeta )
	{
		$this -> model = $eMeta ;
	}

	public function create ( int $userId , int $metaKeyId , string $value ): Meta
	{
		$eMeta = $this
			-> model
			-> newInstance () ;

		$eMeta -> user_id = $userId ;
		$eMeta -> meta_key_id = $metaKeyId ;
		$eMeta -> value = $value ;

		$eMeta -> save () ;

		$meta = new Meta() ;

		$meta -> id = $eMeta -> id ;
		$meta -> user_id = $eMeta -> user_id ;
		$meta -> meta_key_id = $eMeta -> meta_key_id ;
		$meta -> value = $eMeta -> value ;
		$meta -> created_at = $eMeta -> created_at ;
		$meta -> updated_at = $eMeta -> updated_at ;

		return $meta ;
	}

	public function getAllByUserId ( int $userId ): array
	{
		$eMetas = $this
			-> model
			-> where ( 'user_id' , '=' , $userId )
			-> get () ;

		$metas = [] ;

		foreach ( $eMetas as $eMeta )
		{
			$meta = new Meta() ;

			$meta -> id = $eMeta -> id ;
			$meta -> user_id = $eMeta -> user_id ;
			$meta -> meta_key_id = $eMeta -> meta_key_id ;
			$meta -> value = $eMeta -> value ;
			$meta -> created_at = $eMeta -> created_at ;
			$meta -> updated_at = $eMeta -> updated_at ;

			$metas[] = $meta ;
		}

		return $metas ;
	}

	public function getOneByUserIdAndMetaKey ( int $userId , string $metaKey ): Meta
	{
		$eMeta = $this
			-> model
			-> whereHas ( 'metaKey' , function(Builder $q) use($metaKey)
			{
				$q -> where ( 'key' , '=' , $metaKey ) ;
			} )
			-> where ( 'user_id' , '=' , $userId )
			-> firstOrFail () ;

		$meta = new Meta() ;

		$meta -> id = $eMeta -> id ;
		$meta -> user_id = $eMeta -> user_id ;
		$meta -> meta_key_id = $eMeta -> meta_key_id ;
		$meta -> value = $eMeta -> value ;
		$meta -> created_at = $eMeta -> created_at ;
		$meta -> updated_at = $eMeta -> updated_at ;

		return $meta ;
	}

}
