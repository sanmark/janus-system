<?php

namespace App\API\Tests;

use Tests\TestCase;

/**
 * @codeCoverageIgnore
 */
class Api_Users_UserSecretResetRequests_Execute_Test extends TestCase
{
    private $url;

    protected function setUp()
    {
        parent::setUp();

        $this -> seedDb();
        $this -> url = 'api/users/1/user-secret-reset-requests/execute';
    }

    public function testSuccess()
    {
        $createUrl = 'api/users/1/user-secret-reset-requests';

        $userSecretResetRequestTokenObj = $this
            -> postWithValidAppKeyAndSecretHash($createUrl);

        $token = $userSecretResetRequestTokenObj
                -> getData()
            -> data
            -> token;

        $data = [
            'new_secret' => 'rofl' ,
            'user_secret_reset_request_token' => $token ,
        ];

        $this
            -> postWithValidAppKeyAndSecretHash($this -> url, $data)
            -> assertStatus(200)
            -> assertJson([
                'data' => [
                    'id' => 1 ,
                    'key' => 'user1' ,
                ],
            ]);
    }

    public function testInvalidUserIdCausesError()
    {
        $url = 'api/users/149/user-secret-reset-requests/execute';

        $data = [
            'new_secret' => 'rofl' ,
            'user_secret_reset_request_token' => 'non-existing-token' ,
        ];

        $this
            -> postWithValidAppKeyAndSecretHash($url, $data)
            -> assertStatus(400)
            -> assertJson([
                'errors' => [
                    'user_secret_reset_request_token' => [
                        'not_exists' ,
                    ],
                ],
            ]);
    }

    public function testInvalidUserSecretResetRequestTokenCausesError()
    {
        $url = 'api/users/1/user-secret-reset-requests/execute';

        $data = [
            'new_secret' => 'rofl' ,
            'user_secret_reset_request_token' => 'non-existing-token' ,
        ];

        $this
            -> postWithValidAppKeyAndSecretHash($url, $data)
            -> assertStatus(400)
            -> assertJson([
                'errors' => [
                    'user_secret_reset_request_token' => [
                        'not_exists' ,
                    ],
                ],
            ]);
    }

    public function testEmptyInputsCauseError()
    {
        $this
            -> postWithValidAppKeyAndSecretHash($this -> url)
            -> assertStatus(400)
            -> assertJson([
                'errors' => [
                    'new_secret' => [
                        'required',
                    ] ,
                    'user_secret_reset_request_token' => [
                        'required',
                    ],
                ],
            ]);
    }

    public function testInvalidAppKeyIsRejected()
    {
        $this
            -> postWithInvalidAppKeyAndSecretHash($this -> url)
            -> assertStatus(401)
            -> assertJson([
                'errors' => [] ,
            ]);
    }

    public function testInvalidSecretHashIsRejected()
    {
        $this
            -> postWithValidAppKeyAndInvalidSecretHash($this -> url)
            -> assertStatus(401)
            -> assertJson([
                'errors' => [] ,
            ]);
    }

    public function testNoAppKeyIsRejected()
    {
        $this
            -> post($this -> url)
            -> assertStatus(401)
            -> assertJson([
                'errors' => [] ,
            ]);
    }
}
