<?php

namespace App\Helpers\Tests;

use App\Helpers\ArrayHelper;
use Tests\TestCase;

/**
 * @codeCoverageIgnore
 */
class ArrayHelperTest extends TestCase
{
    public function test_onlyNonEmptyMembers_ok()
    {
        $array = [
            'a' => 'A' ,
            'b' => '' ,
            'c' => null ,
        ];

        $arrayHelper = new ArrayHelper();

        $response = $arrayHelper -> onlyNonEmptyMembers($array);

        $this -> assertSame([
            'a' => 'A' ,
        ], $response);
    }
}
