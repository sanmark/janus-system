<?php

namespace App\Handlers ;

use App\Models\AuthSession ;
use App\Repos\Contracts\IAuthSessionsRepo ;

class AuthSessionsHandler
{

	private $authSessionsRepo ;
	private $usersHandler ;

	public function __construct (
	IAuthSessionsRepo $authSessionsRepo
	, UsersHandler $usersHandler
	)
	{
		$this -> authSessionsRepo = $authSessionsRepo ;
		$this -> usersHandler = $usersHandler ;
	}

	public function create ( string $userKey , string $userSecret ): AuthSession
	{
		$user = $this
			-> usersHandler
			-> getUserIfCredentialsValid ( $userKey , $userSecret ) ;

		$authSession = $this
			-> authSessionsRepo
			-> create ( $user -> id ) ;

		return $authSession ;
	}

	public function getByKey ( string $key ): AuthSession
	{
		$authSession = $this
			-> authSessionsRepo
			-> getByKey ( $key ) ;

		return $authSession ;
	}

}
