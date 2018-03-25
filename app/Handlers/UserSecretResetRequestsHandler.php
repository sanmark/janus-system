<?php

namespace App\Handlers ;

use App\API\Constants\UserInputs\UsersInputConstants ;
use App\Models\User ;
use App\Models\UserSecretResetRequest ;
use App\Repos\Contracts\IUserSecretResetRequestsRepo ;
use App\Repos\Exceptions\RecordNotFoundException ;

class UserSecretResetRequestsHandler
{

	private $userSecretResetRequestsRepo ;
	private $usersHandler ;

	public function __construct (
	IUserSecretResetRequestsRepo $userSecretResetRequestsRepo
	, UsersHandler $usersHandler
	)
	{
		$this -> userSecretResetRequestsRepo = $userSecretResetRequestsRepo ;
		$this -> usersHandler = $usersHandler ;
	}

	public function create ( int $userId ): UserSecretResetRequest
	{
		return $this
				-> userSecretResetRequestsRepo
				-> create ( $userId ) ;
	}

	public function execute ( int $userId , string $userSecretResetRequestToken , string $newSecret ): User
	{
		$userSecretResetRequest = $this
			-> userSecretResetRequestsRepo
			-> getByToken ( $userSecretResetRequestToken ) ;

		if ( $userSecretResetRequest -> user_id != $userId )
		{
			throw new RecordNotFoundException() ;
		}

		$user = $this
			-> usersHandler
			-> update ( $userId , [
			UsersInputConstants::UserSecret => $newSecret ,
			] ) ;

		$this -> deleteOfUser ( $userId ) ;

		return $user ;
	}

	private function deleteOfUser ( int $userId )
	{
		$this
			-> userSecretResetRequestsRepo
			-> deleteOfUser ( $userId ) ;
	}

}
