<?php

namespace App\Repos\Concretes\Eloquent\Repos ;

use App\API\Constants\UserInputs\UsersInputConstants ;
use App\Models\User ;
use App\Repos\Concretes\Eloquent\Models\User as eUser ;
use App\Repos\Contracts\IUsersRepo ;
use App\Repos\Exceptions\RecordNotFoundException ;
use App\Repos\Exceptions\UniqueConstraintFailureException ;
use Hash ;
use Illuminate\Database\Eloquent\ModelNotFoundException ;
use Illuminate\Database\QueryException ;
use function dd ;

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

	public function getByKey ( string $userKey ): User
	{
		try
		{
			$eUser = $this
				-> model
				-> where ( 'key' , '=' , $userKey )
				-> firstOrFail () ;

			$user = new User() ;

			$user -> id = $eUser -> id ;
			$user -> key = $eUser -> key ;
			$user -> secret = $eUser -> secret ;
			$user -> deleted_at = $eUser -> deleted_at ;
			$user -> created_at = $eUser -> created_at ;
			$user -> updated_at = $eUser -> updated_at ;

			return $user ;
		} catch ( ModelNotFoundException $ex )
		{
			throw new RecordNotFoundException() ;
		}
	}

}
