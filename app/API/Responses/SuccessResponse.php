<?php

namespace App\API\Responses ;

class SuccessResponse extends BaseResponse
{

	private $data ;
	private $statusCode ;

	public function __construct ( $input , $statusCode = 200 )
	{
		$this -> data = $input ;
		$this -> statusCode = $statusCode ;
	}

	public function getOutput (): array
	{
		return [
			'data' => $this -> data ,
			] ;
	}

	public function getResponse ()
	{
		return response ()
				-> json ( $this -> getOutput () )
				-> setStatusCode ( $this -> statusCode ) ;
	}

}
