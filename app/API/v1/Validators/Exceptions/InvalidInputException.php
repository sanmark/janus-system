<?php

namespace App\API\v1\Validators\Exceptions ;

use Exception ;
use Throwable ;

class InvalidInputException extends Exception
{

	private $errors ;

	public function __construct ( array $errors = [] , string $message = "" , int $code = 0 , Throwable $previous = null )
	{
		$this -> errors = $errors ;
		parent::__construct ( $message , $code , $previous ) ;
	}

	public function getErrors ()
	{
		return $this -> errors ;
	}

}
