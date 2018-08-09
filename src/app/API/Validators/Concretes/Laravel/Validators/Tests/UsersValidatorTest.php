<?php

namespace App\API\Validators\Concretes\Laravel\Validators\Tests;

use App\API\Validators\Concretes\Laravel\Validators\UsersValidator;
use App\API\Validators\Exceptions\InvalidInputException;
use Tests\TestCase;

/**
 * @codeCoverageIgnore
 */
class UsersValidatorTest extends TestCase
{
    public function testCreate_ReturnsNullForValidInputs()
    {
        $data = [
            'user_key' => 'the_key' ,
            'user_secret' => 'the_secret' ,
        ];

        $validator = new UsersValidator();

        $result = $validator -> create($data);

        $this -> assertNull($result);
    }

    public function testCreate_ThrowsInvalidInputExceptionForInvalidInputs()
    {
        $data = [];

        $validator = new UsersValidator();

        try {
            $validator -> create($data);
        } catch (InvalidInputException $ex) {
            $this -> assertInstanceOf(InvalidInputException::class, $ex);

            $this -> assertEquals([
                'user_key' => [
                    'required' ,
                ] ,
                'user_secret' => [
                    'required' ,
                ] ,
            ], $ex -> getErrors());
        }
    }

    public function test_userSecretResetRequestsExecute_returnsNullForValidInput()
    {
        $data = [
            'new_secret' => 'new-secret' ,
            'user_secret_reset_request_token' => 'the-token' ,
        ];

        $validator = new UsersValidator();

        $response = $validator -> userSecretResetRequestsExecute($data);

        $this -> assertNull($response);
    }

    public function test_userSecretResetRequestsExecute_throwsInvalidInputExceptionForInvalidInputs()
    {
        try {
            $validator = new UsersValidator();

            $data = [];
            $validator -> userSecretResetRequestsExecute($data);
        } catch (InvalidInputException $ex) {
            $this -> assertInstanceOf(InvalidInputException::class, $ex);

            $this -> assertEquals([
                'new_secret' => [
                    'required' ,
                ] ,
                'user_secret_reset_request_token' => [
                    'required' ,
                ] ,
            ], $ex -> getErrors());
        }
    }
}
