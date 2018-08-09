<?php

namespace App\API\Responses;

abstract class BaseResponse
{
    abstract public function __construct($input);

    abstract public function getOutput(): array;

    abstract public function getResponse();
}
