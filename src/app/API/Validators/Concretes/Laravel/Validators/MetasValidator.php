<?php

namespace App\API\Validators\Concretes\Laravel\Validators;

use App\API\Validators\Concretes\Laravel\Constants\RuleConstants;
use App\API\Validators\Constants\ResponseConstants;
use App\API\Validators\Contracts\IMetasValidator;

class MetasValidator extends BaseValidator implements IMetasValidator
{
    public function createByUserIdAndMetaKey(array $data)
    {
        $rules = [
            'value' => [
                RuleConstants::Required ,
            ] ,
        ];

        $messages = [
            'value' => [
                RuleConstants::Required => ResponseConstants::Required ,
            ] ,
        ];

        $this -> validate($data, $rules, $messages);
    }
}
