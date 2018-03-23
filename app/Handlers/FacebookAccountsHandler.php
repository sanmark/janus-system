<?php

namespace App\Handlers ;

use App\Models\AuthSession ;
use App\Models\FacebookAccount ;
use App\Repos\Contracts\IFacebookAccountsRepo ;
use App\Repos\Exceptions\RecordNotFoundException ;
use Carbon\Carbon ;
use Illuminate\Contracts\Hashing\Hasher ;

class FacebookAccountsHandler
{

	private $authSessionsHandler ;
	private $carbon ;
	private $facebookAccountsRepo ;
	private $hash ;
	private $usersHandler ;

	public function __construct (
	AuthSessionsHandler $authSessionsHandler
	, Carbon $carbon
	, IFacebookAccountsRepo $facebookAccountsRepo
	, Hasher $hasher
	, UsersHandler $usersHandler
	)
	{
		$this -> authSessionsHandler = $authSessionsHandler ;
		$this -> carbon = $carbon ;
		$this -> facebookAccountsRepo = $facebookAccountsRepo ;
		$this -> hash = $hasher ;
		$this -> usersHandler = $usersHandler ;
	}

	public function create ( string $key , string $firstName ): FacebookAccount
	{
		$userKey = $this -> generateUserKeyFromFirstName ( $firstName ) ;
		$userSecret = $this -> hash -> make ( $this -> carbon -> now () ) ;

		$user = $this
			-> usersHandler
			-> create ( $userKey , $userSecret ) ;

		$facebookAccount = $this
			-> facebookAccountsRepo
			-> create ( $user -> id , $key ) ;

		return $facebookAccount ;
	}

	public function getAuthSession ( string $key , string $firstName ): AuthSession
	{
		$facebookAccount = NULL ;

		try
		{
			$facebookAccount = $this -> getByKey ( $key ) ;
		} catch ( RecordNotFoundException $ex )
		{
			$facebookAccount = $this -> create ( $key , $firstName ) ;
		}

		$user = $this
			-> usersHandler
			-> get ( $facebookAccount -> user_id ) ;

		$authSession = $this
			-> authSessionsHandler
			-> createForUserObject ( $user ) ;

		return $authSession ;
	}

	public function getByKey ( string $key ): FacebookAccount
	{
		$facebookAccount = $this
			-> facebookAccountsRepo
			-> getByKey ( $key ) ;

		return $facebookAccount ;
	}

	private function generateUserKeyFromFirstName ( string $firstName ): string
	{
		$firstNameAllLowercase = strtolower ( $firstName ) ;
		$randomNumber = rand ( 1 , 9999 ) ;

		$userKey = $firstNameAllLowercase . $randomNumber ;

		return $userKey ;
	}

}
