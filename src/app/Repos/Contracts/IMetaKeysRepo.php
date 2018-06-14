<?php

namespace App\Repos\Contracts ;

use App\Models\MetaKey ;

interface IMetaKeysRepo
{

	public function find ( $id ) ;

	public function all (): array ;

	public function getByKey ( string $key ): MetaKey ;

	public function getUsersForMetaValue ( string $metaKey , string $metaValue ): array ;
}
