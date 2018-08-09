<?php

namespace App\API\Tests;

use Tests\TestCase;

/**
 * @codeCoverageIgnore
 */
class Api_AuthSessions_Validate_Test extends TestCase
{
    public function testSystemRejectsInvalidAppKey()
    {
        $this -> seedDb();

        $this
            -> withHeader('x-lk-sanmark-janus-sessionkey', 'the_auth_session_key')
            -> getWithInvalidAppKeyAndSecretHash('api/auth-sessions/validate')
            -> assertStatus(401)
            -> assertJson([
                'errors' => [
                ] ,
            ]);
    }

    public function testSystemRejectsInvalidSecretHash()
    {
        $this -> seedDb();

        $this
            -> withHeader('x-lk-sanmark-janus-sessionkey', 'the_auth_session_key')
            -> getWithValidAppKeyAndInvalidSecretHash('api/auth-sessions/validate')
            -> assertStatus(401)
            -> assertJson([
                'errors' => [
                ] ,
            ]);
    }

    public function testSystemRejectsNoAppKey()
    {
        $this -> seedDb();

        $this
            -> withHeader('x-lk-sanmark-janus-sessionkey', 'the_auth_session_key')
            -> get('api/auth-sessions/validate')
            -> assertStatus(401)
            -> assertJson([
                'errors' => [
                ] ,
            ]);
    }

    public function testUserCanValidateAuthSession()
    {
        $this -> seedDb();

        $this
            -> withHeader('x-lk-sanmark-janus-sessionkey', 'the_auth_session_key')
            -> getWithValidAppKeyAndSecretHash('api/auth-sessions/validate')
            -> assertStatus(200)
            -> assertJsonStructure([
                'data' => [
                    'id' ,
                    'key' ,
                    'user_id' ,
                    'created_at' ,
                    'updated_at' ,
                ] ,
            ]);
    }

    public function testSystemRejectsNullSessionKey()
    {
        $this -> seedDb();

        $this
            -> getWithValidAppKeyAndSecretHash('api/auth-sessions/validate')
            -> assertStatus(401)
            -> assertJsonStructure([]);
    }

    public function testSystemRejectsInvalidSessionKey()
    {
        $this -> seedDb();

        $this
            -> withHeader('x-lk-sanmark-janus-sessionkey', 'wrong')
            -> getWithValidAppKeyAndSecretHash('api/auth-sessions/validate')
            -> assertStatus(401)
            -> assertJsonStructure([]);
    }

    public function testSystemRejectsExpiredSessionKey()
    {
        $this -> seedDb();

        $this
            -> withHeader('x-lk-sanmark-janus-sessionkey', 'the_auth_session_key_expired')
            -> getWithValidAppKeyAndSecretHash('api/auth-sessions/validate')
            -> assertStatus(401)
            -> assertJsonStructure([]);
    }
}
