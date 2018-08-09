<?php

namespace App\Models\Tests;

use App\Models\User;
use Tests\TestCase;

/**
 * @codeCoverageIgnore
 */
class BaseTest extends TestCase
{
    public function test_toString_ok()
    {
        $user = new User();

        $this -> assertSame('{"id":null,"key":null,"secret":null}', $user -> __toString());
    }

    public function testToArray_Ok()
    {
        $user = new User();

        $user -> id = $this -> faker() -> numberBetween();
        $user -> key = $this -> faker() -> userName;
        $user -> secret = $this -> faker() -> password;

        $this -> assertSame([
            'id' => $user -> id ,
            'key' => $user -> key ,
            'secret' => $user -> secret ,
        ], $user -> toArray());
    }

    public function testToArrayOnly_Ok()
    {
        $user = new User();

        $user -> id = $this -> faker() -> numberBetween();
        $user -> key = $this -> faker() -> userName;
        $user -> secret = $this -> faker() -> password;

        $this -> assertSame([
            'id' => $user -> id ,
            'secret' => $user -> secret ,
        ], $user -> toArrayOnly([
            'id' ,
            'secret' ,
        ]));
    }
}
