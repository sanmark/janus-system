<?php

namespace App\Repos\Contracts ;

use App\Models\Meta ;

interface IMetasRepo
{

	public function create ( int $userId , int $metaKeyId , string $value ): Meta ;

	public function getAllByUserId ( int $userId ): array ;

	public function getOneByUserIdAndMetaKey ( int $userId , string $metaKey ): Meta ;

	public function update ( int $metaId , string $value ): Meta ;
}
