<?php

namespace App\API\Tests;

use Tests\TestCase;

/**
 * @codeCoverageIgnore
 */
class Api_AuthSessions_Create_Test extends TestCase
{
    public function test_login_systemRejectsNoAppKey()
    {
        $data = [
            'user_key' => 'user1' ,
            'user_secret' => 'sec1' ,
        ];

        $this
            -> post('api/auth-sessions', $data)
            -> assertStatus(401)
            -> assertJsonStructure([
                'errors' => [
                ] ,
            ]);
    }

    public function test_login_systemRejectsInvalidAppKey()
    {
        $data = [
            'user_key' => 'user1' ,
            'user_secret' => 'sec1' ,
        ];

        $this
            -> postWithInvalidAppKeyAndSecretHash('api/auth-sessions', $data)
            -> assertStatus(401)
            -> assertJsonStructure([
                'errors' => [
                ] ,
            ]);
    }

    public function test_login_systemRejectsInvalidSecretHash()
    {
        $this -> seedDb();

        $data = [
            'user_key' => 'user1' ,
            'user_secret' => 'sec1' ,
        ];

        $this
            -> postWithValidAppKeyAndInvalidSecretHash('api/auth-sessions', $data)
            -> assertStatus(401)
            -> assertJsonStructure([
                'errors' => [
                ] ,
            ]);
    }

    public function test_login_ok()
    {
        $this -> seedDb();

        $data = [
            'user_key' => 'user1' ,
            'user_secret' => 'sec1' ,
        ];

        $this
            -> postWithValidAppKeyAndSecretHash('api/auth-sessions', $data)
            -> assertStatus(201)
            -> assertJsonStructure([
                'data' => [
                    'key' ,
                ] ,
            ]);
    }

    public function test_login_systemValidatesUserInputs()
    {
        $this -> seedDb();

        $this
            -> postWithValidAppKeyAndSecretHash('api/auth-sessions')
            -> assertStatus(400)
            -> assertJson([
                'errors' => [
                    'user_key' => [
                        'required' ,
                    ] ,
                    'user_secret' => [
                        'required' ,
                    ] ,
                ] ,
            ]);
    }

    public function test_login_systemRejectsInvalidUserKeys()
    {
        $this -> seedDb();

        $data = [
            'user_key' => 'wrong1' ,
            'user_secret' => 'sec1' ,
        ];

        $this
            -> postWithValidAppKeyAndSecretHash('api/auth-sessions', $data)
            -> assertStatus(401);
    }

    public function test_login_systemRejectsInvalidUserSecrets()
    {
        $this -> seedDb();

        $data = [
            'user_key' => 'user1' ,
            'user_secret' => 'wrong1' ,
        ];

        $this
            -> postWithValidAppKeyAndSecretHash('api/auth-sessions', $data)
            -> assertStatus(401);
    }
}
