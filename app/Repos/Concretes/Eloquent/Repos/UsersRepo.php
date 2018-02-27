<?php

namespace App\Repos\Concretes\Eloquent\Repos ;

use App\API\v1\Constants\UserInputs\UsersInputConstants ;
use App\Models\User ;
use App\Repos\Concretes\Eloquent\Models\User as eUser ;
use App\Repos\Contracts\IUsersRepo ;
use App\Repos\Exceptions\UniqueConstraintFailureException ;
use Hash ;
use Illuminate\Database\QueryException ;

class UsersRepo implements IUsersRepo
{

	private $model ;

	public function __construct ( eUser $eUser )
	{
		$this -> model = $eUser ;
	}

	public function create ( string $userKey , string $userSecret ): User
	{
		try
		{
			$eUser = $this
				-> model
				-> newInstance () ;

			$eUser -> key = $userKey ;
			$eUser -> secret = Hash::make ( $userSecret ) ;

			$eUser -> save () ;

			$user = new User() ;

			$user -> id = $eUser -> id ;
			$user -> key = $eUser -> key ;

			return $user ;
		} catch ( QueryException $ex )
		{
			throw new UniqueConstraintFailureException ( UsersInputConstants::UserKey , $userKey ) ;
		}
	}

}
