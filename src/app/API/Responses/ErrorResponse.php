<?php

namespace App\API\Responses ;

class ErrorResponse extends BaseResponse
{

	private $errors ;
	private $statusCode ;

	public function __construct ( $input , $statusCode = null )
	{
		$this -> errors = $input ;
		$this -> statusCode = $statusCode ;
	}

	public function getOutput (): array
	{
		return [
			'errors' => $this -> errors ,
			] ;
	}

	public function getResponse ()
	{
		return response ()
				-> json ( $this -> getOutput () )
				-> setStatusCode ( $this -> statusCode ) ;
	}

}
