<?php

namespace App\API\v1\Responses ;

class SuccessResponse extends BaseResponse
{

	private $data ;

	public function __construct ( $input )
	{
		$this -> data = $input ;
	}

	public function getOutput (): array
	{
		return [
			'data' => $this -> data ,
		] ;
	}

}
