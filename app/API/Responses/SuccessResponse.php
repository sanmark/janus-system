<?php

namespace App\API\Responses ;

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
