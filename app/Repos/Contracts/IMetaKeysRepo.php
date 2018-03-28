<?php

namespace App\Repos\Contracts ;

interface IMetaKeysRepo
{

	public function find ( $id ) ;

	public function all () ;

	public function getMetasForUser ( $userID ) ;

	public function getOneMetaForUser ( $userID , $metaKey ) ;

	public function saveMetas ( $userID , array $data ) ;

	public function getUsersForMetaValue ( string $metaKey , string $metaValue ): array ;
}
