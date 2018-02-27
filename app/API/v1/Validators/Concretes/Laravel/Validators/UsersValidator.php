<?php

namespace App\API\v1\Validators\Concretes\Laravel\Validators ;

use App\API\v1\Constants\UserInputs\UsersInputConstants ;
use App\API\v1\Validators\Concretes\Laravel\Constants\RuleConstants ;
use App\API\v1\Validators\Constants\ResponseConstants ;
use App\API\v1\Validators\Contracts\IUsersValidator ;
use App\API\v1\Validators\Exceptions\InvalidInputException ;
use function validator ;

class UsersValidator implements IUsersValidator
{

	public function create ( array $d )
	{
		$r = [
			UsersInputConstants::UserKey => [
				RuleConstants::Required ,
			] ,
			UsersInputConstants::UserSecret => [
				RuleConstants::Required ,
			] ,
			] ;

		$m = [
			UsersInputConstants::UserKey . '.' . RuleConstants::Required => ResponseConstants::Required ,
			UsersInputConstants::UserSecret . '.' . RuleConstants::Required => ResponseConstants::Required ,
			] ;

		$v = validator ( $d , $r , $m ) ;

		if ( $v -> fails () )
		{
			$e = $v
				-> errors ()
				-> toArray () ;

			throw new InvalidInputException ( $e ) ;
		}
	}

}
