<?php

namespace App\API\Validators\Concretes\Laravel\Validators ;

use App\API\Constants\Inputs\MetasInputConstants;
use App\API\Validators\Concretes\Laravel\Constants\RuleConstants;
use App\API\Validators\Constants\ResponseConstants;
use App\API\Validators\Contracts\IMetasValidator;

class MetasValidators extends BaseValidator implements IMetasValidator
{

	public function create ( array $d )
	{
		$r = [
			MetasInputConstants::Key => [
				RuleConstants::Required ,
				RuleConstants::Exists . ':meta_keys,key' ,
			] ,
			MetasInputConstants::Value => [
				RuleConstants::Required ,
			]
			] ;

		$m = [
			MetasInputConstants::Key => [
				RuleConstants::Required => ResponseConstants::Required ,
				RuleConstants::Exists => ResponseConstants::NotExists ,
			] ,
			MetasInputConstants::Value => [
				RuleConstants::Required => ResponseConstants::Required ,
			]
			] ;

		$this -> validate ( $d , $r , $m ) ;
	}

}
