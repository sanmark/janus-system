<?php

namespace App\Handlers ;

use App\Models\AuthSession ;
use App\Repos\Contracts\IAuthSessionsRepo ;
use App\Repos\Exceptions\RecordNotFoundException ;
use App\SystemSettings\Contracts\ISystemSettingsInterface ;
use Carbon\Carbon ;

class AuthSessionsHandler
{

	private $authSessionsRepo ;
	private $carbon ;
	private $systemSettingsInterface ;
	private $usersHandler ;

	public function __construct (
	IAuthSessionsRepo $authSessionsRepo
	, ISystemSettingsInterface $systemSettingsInterface
	, UsersHandler $usersHandler
	, Carbon $carbon
	)
	{
		$this -> authSessionsRepo = $authSessionsRepo ;
		$this -> carbon = $carbon ;
		$this -> systemSettingsInterface = $systemSettingsInterface ;
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

	public function extendActiveTime ( AuthSession $authSession , int $authSessionActiveMinutes ): AuthSession
	{
		$authSession
			-> updated_at
			-> addMinutes ( $authSessionActiveMinutes ) ;

		return $this
				-> authSessionsRepo
				-> update ( $authSession ) ;
	}

	public function getByKey ( string $key ): AuthSession
	{
		$authSession = $this
			-> authSessionsRepo
			-> getByKey ( $key ) ;

		return $authSession ;
	}

	public function getByKeyIfActiveAndExtendActiveTime ( string $key ): AuthSession
	{
		$authSession = $this -> getByKey ( $key ) ;

		$authSessionActiveMinutes = $this
			-> systemSettingsInterface
			-> getAuthSessionActiveMinutes () ;

		$expiryTime = $authSession
			-> updated_at
			-> copy ()
			-> addMinutes ( $authSessionActiveMinutes ) ;

		$now = $this -> carbon -> now () ;

		if ( $now > $expiryTime )
		{
			throw new RecordNotFoundException() ;
		}

		return $this -> extendActiveTime ( $authSession , $authSessionActiveMinutes ) ;
	}

}
