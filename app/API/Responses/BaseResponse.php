<?php

namespace App\API\Responses ;

abstract class BaseResponse
{

	public function __toString ()
	{
		return json_encode ( $this -> getResponse () ) ;
	}

	abstract public function __construct ( $input ) ;

	abstract public function getOutput (): array ;

	abstract public function getResponse () ;
}
