<?php

namespace App\API\v1\Responses ;

abstract class BaseResponse
{

	abstract public function __construct ( $input ) ;

	abstract public function getOutput (): array ;
}
