<?php

namespace App\Repos\Exceptions ;

use Exception ;
use Throwable ;

class UniqueConstraintFailureException extends Exception
{

	private $constraint ;
	private $value ;

	public function __construct ( string $constraint = null , string $value = null , string $message = "" , int $code = 0 , Throwable $previous = null )
	{
		$this -> constraint = $constraint ;
		$this -> value = $value ;

		parent::__construct ( $message , $code , $previous ) ;
	}

	public function getConstraint ()
	{
		return $this -> constraint ;
	}

	public function getValue ()
	{
		return $this -> value ;
	}

}
