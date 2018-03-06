<?php

namespace App\Repos\Concretes\Eloquent\Repos ;

use App\Models\AuthSession ;
use App\Repos\Concretes\Eloquent\Models\AuthSession as eAuthSession ;
use App\Repos\Contracts\IAuthSessionsRepo ;
use Hash ;
use Illuminate\Contracts\Hashing\Hasher ;
use function str_random ;

class AuthSessionsRepo implements IAuthSessionsRepo
{

	private $model ;
	private $usersRepo ;
	private $hash ;

	public function __construct (
	Hasher $hash
	, eAuthSession $eAuthSession
	, UsersRepo $usersRepo
	)
	{
		$this -> hash = $hash ;
		$this -> model = $eAuthSession ;
		$this -> usersRepo = $usersRepo ;
	}

	public function create ( int $userId ): AuthSession
	{
		$eAuthSession = $this
			-> model
			-> newInstance () ;

		$user = $this
			-> usersRepo
			-> get ( $userId ) ;

		$eAuthSession -> key = $this
			-> hash
			-> make ( str_random () ) ;
		$eAuthSession -> user_id = $user -> id ;

		$eAuthSession -> save () ;

		$authSession = new AuthSession() ;
		$authSession -> id = $eAuthSession -> id ;
		$authSession -> key = $eAuthSession -> key ;
		$authSession -> user_id = $user -> id ;

		return $authSession ;
	}

}
