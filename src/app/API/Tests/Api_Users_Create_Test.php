<?php

namespace App\API\Tests;

use Tests\TestCase;

/**
 * @codeCoverageIgnore
 */
class Api_Users_Create_Test extends TestCase
{
    public function testSystemRejectsInvalidAppKey()
    {
        $this -> seedDb();

        $data = [
            'user_key' => $this -> faker() -> userName ,
            'user_secret' => $this -> faker() -> password ,
        ];

        $this
            -> postWithInvalidAppKeyAndSecretHash('api/users', $data)
            -> assertStatus(401)
            -> assertJson([
                'errors' => [
                ] ,
            ]);
    }

    public function testSystemRejectsInvalidSecretHash()
    {
        $this -> seedDb();

        $data = [
            'user_key' => $this -> faker() -> userName ,
            'user_secret' => $this -> faker() -> password ,
        ];

        $this
            -> postWithValidAppKeyAndInvalidSecretHash('api/users', $data)
            -> assertStatus(401)
            -> assertJson([
                'errors' => [
                ] ,
            ]);
    }

    public function testSystemRejectsNoAppKey()
    {
        $this -> seedDb();

        $data = [
            'user_key' => $this -> faker() -> userName ,
            'user_secret' => $this -> faker() -> password ,
        ];

        $this
            -> post('api/users', $data)
            -> assertStatus(401)
            -> assertJson([
                'errors' => [
                ] ,
            ]);
    }

    public function testUserCanRegister()
    {
        $this -> seedDb();

        $data = [
            'user_key' => $this -> faker() -> userName ,
            'user_secret' => $this -> faker() -> password ,
        ];

        $this
            -> postWithValidAppKeyAndSecretHash('api/users', $data)
            -> assertStatus(201)
            -> assertJson([
                'data' => [
                    'key' => $data[ 'user_key' ] ,
                ] ,
            ])
            -> assertJsonStructure([
                'data' => [
                    'id' ,
                ] ,
            ]);
    }

    public function testSystemValidatesUserInputs()
    {
        $this -> seedDb();

        $this
            -> postWithValidAppKeyAndSecretHash('api/users')
            -> assertStatus(400)
            -> assertJson([
                'errors' => [
                    'user_key' => [
                        'required',
                    ] ,
                    'user_secret' => [
                        'required',
                    ] ,
                ] ,
            ]);
    }

    public function testSystemRejectsDuplicateUserKeys()
    {
        $this -> seedDb();

        $data = [
            'user_key' => $this -> faker() -> userName ,
            'user_secret' => $this -> faker() -> password ,
        ];

        $this -> postWithValidAppKeyAndSecretHash('api/users', $data);

        $this
            -> postWithValidAppKeyAndSecretHash('api/users', $data)
            -> assertStatus(409)
            -> assertJson([
                'errors' => [
                    'user_key' => 'duplicate' ,
                ] ,
            ]);
    }
}
