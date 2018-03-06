<?php

namespace App\Handlers ;

use App\Models\User ;
use App\Repos\Contracts\IUsersRepo ;
use App\Repos\Exceptions\RecordNotFoundException ;
use Illuminate\Contracts\Hashing\Hasher ;

class UsersHandler
{

	private $hash ;
	private $usersRepo ;

	public function __construct (
	Hasher $hash
	, IUsersRepo $iUsersRepo
	)
	{
		$this -> hash = $hash ;
		$this -> usersRepo = $iUsersRepo ;
	}

	public function create ( string $userKey , string $userSecret ): User
	{
		return $this
				-> usersRepo
				-> create ( $userKey , $userSecret ) ;
	}

	public function getUserIfCredentialsValid ( string $userKey , string $userSecret ): User
	{
		$user = $this
			-> usersRepo
			-> getByKey ( $userKey ) ;

		if ( $this -> hash -> check ( $userSecret , $user -> secret ) )
		{
			return $user ;
		}

		throw new RecordNotFoundException() ;
	}

}
