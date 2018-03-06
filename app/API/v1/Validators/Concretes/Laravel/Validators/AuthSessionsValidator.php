<?php

namespace App\API\v1\Validators\Concretes\Laravel\Validators ;

use App\API\v1\Constants\UserInputs\UsersInputConstants ;
use App\API\v1\Validators\Concretes\Laravel\Constants\RuleConstants ;
use App\API\v1\Validators\Constants\ResponseConstants ;
use App\API\v1\Validators\Contracts\IAuthSessionsValidator ;
use App\API\v1\Validators\Exceptions\InvalidInputException ;
use function validator ;

class AuthSessionsValidator extends BaseValidator implements IAuthSessionsValidator
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
			UsersInputConstants::UserKey => [
				RuleConstants::Required => ResponseConstants::Required ,
			] ,
			UsersInputConstants::UserSecret => [
				RuleConstants::Required => ResponseConstants::Required ,
			] ,
			] ;

		$this -> validate ( $d , $r , $m ) ;
	}

}
