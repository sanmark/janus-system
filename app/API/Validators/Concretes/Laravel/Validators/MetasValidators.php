<?php

namespace App\API\Validators\Concretes\Laravel\Validators ;

use App\API\Validators\Concretes\Laravel\Constants\RuleConstants ;
use App\API\Validators\Constants\ResponseConstants ;
use App\API\Validators\Contracts\IMetasValidator ;
use App\Rules\MetaKeyRule ;

class MetasValidators extends BaseValidator implements IMetasValidator
{

	public function saveMetas ( array $data )
	{
		$rules = [] ;
		$messeges = [] ;
		foreach ( $data as $key => $item )
		{
			$rules [ $key ] = [
				RuleConstants::Required ,
				new MetaKeyRule()
				] ;

			$messeges[ $key ] = [
				RuleConstants::Required => ResponseConstants::Required ,
				] ;
		}

		$this -> validate ( $data , $rules , $messeges ) ;
	}

}
