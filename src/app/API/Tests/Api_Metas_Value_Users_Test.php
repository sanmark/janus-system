<?php

namespace App\API\Tests;

use Tests\TestCase;

/**
 * @codeCoverageIgnore
 */
class Api_Metas_Value_Users_Test extends TestCase
{
    private $url;

    protected function setUp()
    {
        parent::setUp();

        $this -> seedDb();
        $this -> url = 'api/metas/demo-meta-1/value/demo-meta-1-value/users';
    }

    public function testCorrectDataAreReturned()
    {
        $this
            -> getWithValidAppKeyAndSecretHash($this -> url)
            -> assertStatus(200)
            -> assertJson([
                'data' => [
                    [
                        'id' => 1 ,
                        'key' => 'user1' ,
                        'secret' => '' ,
                    ] ,
                ] ,
            ]);
    }

    public function testInvalidAppKeyIsRejected()
    {
        $this
            -> getWithInvalidAppKeyAndSecretHash($this -> url)
            -> assertStatus(401)
            -> assertJson([
                'errors' => [] ,
            ]);
    }

    public function testInvalidSecretHashIsRejected()
    {
        $this
            -> getWithValidAppKeyAndInvalidSecretHash($this -> url)
            -> assertStatus(401)
            -> assertJson([
                'errors' => [] ,
            ]);
    }

    public function testNoAppKeyIsRejected()
    {
        $this
            -> get($this -> url)
            -> assertStatus(401)
            -> assertJson([
                'errors' => [] ,
            ]);
    }
}
