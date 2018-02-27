<?php

namespace App\Handlers ;

use App\Models\User ;
use App\Repos\Contracts\IUsersRepo ;

class UsersHandler
{

	private $usersRepo ;

	public function __construct ( IUsersRepo $iUsersRepo )
	{
		$this -> usersRepo = $iUsersRepo ;
	}

	public function create ( string $userKey , string $userSecret ): User
	{
		return $this
				-> usersRepo
				-> create ( $userKey , $userSecret ) ;
	}

}
