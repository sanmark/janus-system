<?php

namespace App\Repos\Contracts ;

use App\Models\User ;

interface IUsersRepo
{

	public function create ( string $userKey , string $userSecret ): User ;

	public function get ( int $userId ): User ;

	public function getByKey ( string $userKey ): User ;
}
