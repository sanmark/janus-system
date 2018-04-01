<?php

namespace App\API\Validators\Concretes\Laravel\Validators ;

use App\API\Constants\Inputs\UsersInputConstants ;
use App\API\Validators\Concretes\Laravel\Constants\RuleConstants ;
use App\API\Validators\Constants\ResponseConstants ;
use App\API\Validators\Contracts\IUsersValidator ;
use App\API\Validators\Exceptions\InvalidInputException ;
use function validator ;

class UsersValidator extends BaseValidator implements IUsersValidator
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

	public function userSecretResetRequestsExecute ( array $d )
	{
		$r = [
			UsersInputConstants::NewSecret => [
				RuleConstants::Required ,
			] ,
			UsersInputConstants::UserSecretResetRequestToken => [
				RuleConstants::Required ,
			] ,
			] ;

		$m = [
			UsersInputConstants::NewSecret => [
				RuleConstants::Required => ResponseConstants::Required ,
			] ,
			UsersInputConstants::UserSecretResetRequestToken => [
				RuleConstants::Required => ResponseConstants::Required ,
			] ,
			] ;

		$this -> validate ( $d , $r , $m ) ;
	}

}
