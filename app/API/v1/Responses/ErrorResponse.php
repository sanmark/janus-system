<?php

namespace App\API\v1\Responses ;

class ErrorResponse extends BaseResponse
{

	private $errors ;

	public function __construct ( $input )
	{
		$this -> errors = $input ;
	}

	public function getOutput (): array
	{
		return [
			'errors' => $this -> errors ,
		] ;
	}

}
