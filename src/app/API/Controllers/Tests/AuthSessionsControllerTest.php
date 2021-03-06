<?php

namespace App\API\Controllers\Tests;

use App\API\Controllers\AuthSessionsController;
use App\API\Validators\Concretes\Laravel\Validators\AuthSessionsValidator;
use App\API\Validators\Exceptions\InvalidInputException;
use App\Handlers\AuthSessionsHandler;
use App\Models\AuthSession;
use App\Repos\Exceptions\RecordNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @codeCoverageIgnore
 */
class AuthSessionsControllerTest extends TestCase
{
    public function testCreate_Ok()
    {
        $authSessionsHandler = $this -> mock(AuthSessionsHandler::class);
        $authSessionsValidator = $this -> mock(AuthSessionsValidator::class);

        $authSessionsController = new AuthSessionsController($authSessionsHandler, $authSessionsValidator);

        $request = $this -> mock(Request::class);

        $request
            -> shouldReceive('toArray')
            -> andReturn([
                149 ,
            ]);

        $authSessionsValidator
            -> shouldReceive('create')
            -> withArgs([
                [
                    149 ,
                ] ,
            ]);

        $request
            -> shouldReceive('get')
            -> withArgs([
                'user_key' ,
            ])
            -> andReturn('the_key');

        $request
            -> shouldReceive('get')
            -> withArgs([
                'user_secret' ,
            ])
            -> andReturn('the_secret');

        $authSession = $this -> mock(AuthSession::class);
        $authSession -> id = 149;
        $authSession -> key = 'the_key_2';
        $authSession -> user_id = 'the_user_id';

        $authSessionsHandler
            -> shouldReceive('create')
            -> withArgs([
                'the_key' ,
                'the_secret' ,
            ])
            -> andReturn($authSession);

        $response = $authSessionsController -> create($request);

        $this -> assertInstanceOf(JsonResponse::class, $response);
        $this -> assertEquals(201, $response -> getStatusCode());
        $this -> assertEquals((object) [
            'data' => (object) [
                'id' => 149 ,
                'key' => 'the_key_2' ,
                'user_id' => 'the_user_id' ,
            ] ,
        ], $response -> getData());
    }

    public function testCreate_HandlesInvalidInputException()
    {
        $authSessionsHandler = $this -> mock(AuthSessionsHandler::class);
        $authSessionsValidator = $this -> mock(AuthSessionsValidator::class);

        $authSessionsController = new AuthSessionsController($authSessionsHandler, $authSessionsValidator);

        $request = $this -> mock(Request::class);

        $request
            -> shouldReceive('toArray')
            -> andReturn([
                149 ,
            ]);

        $invalidInputException = $this -> mock(InvalidInputException::class);

        $authSessionsValidator
            -> shouldReceive('create')
            -> withArgs([
                [
                    149 ,
                ] ,
            ])
            -> andThrow($invalidInputException);

        $invalidInputException
            -> shouldReceive('getErrors')
            -> andReturn(150);

        $respose = $authSessionsController -> create($request);

        $this -> assertInstanceOf(JsonResponse::class, $respose);
        $this -> assertEquals(400, $respose -> getStatusCode());
        $this -> assertEquals((object) [
            'errors' => 150 ,
        ], $respose -> getData());
    }

    public function testCreate_HandlesRecordNotFoundException()
    {
        $authSessionsHandler = $this -> mock(AuthSessionsHandler::class);
        $authSessionsValidator = $this -> mock(AuthSessionsValidator::class);

        $authSessionsController = new AuthSessionsController($authSessionsHandler, $authSessionsValidator);

        $request = $this -> mock(Request::class);

        $request
            -> shouldReceive('toArray')
            -> andReturn([
                149 ,
            ]);

        $invalidInputException = $this -> mock(InvalidInputException::class);

        $authSessionsValidator
            -> shouldReceive('create')
            -> withArgs([
                [
                    149 ,
                ] ,
            ]);

        $requestAttributes = [
            'user_key' => 'the_user_key' ,
            'user_secret' => 'the_user_secret' ,
        ];

        foreach ($requestAttributes as $key => $value) {
            $request
                -> shouldReceive('get')
                -> withArgs([
                    $key ,
                ])
                -> andReturns($value);
        }

        $recordNotFoundException = $this -> mock(RecordNotFoundException::class);

        $authSessionsHandler
            -> shouldReceive('create')
            -> withArgs([
                'the_user_key' ,
                'the_user_secret' ,
            ])
            -> andThrow($recordNotFoundException);

        $respose = $authSessionsController -> create($request);

        $this -> assertInstanceOf(JsonResponse::class, $respose);
        $this -> assertEquals(401, $respose -> getStatusCode());
    }

    public function testValidate_Ok()
    {
        $authSessionsHandler = $this -> mock(AuthSessionsHandler::class);
        $authSessionsValidator = $this -> mock(AuthSessionsValidator::class);

        $authSessionsController = new AuthSessionsController($authSessionsHandler, $authSessionsValidator);

        $request = $this -> mock(Request::class);

        $request
            -> shouldReceive('header')
            -> withArgs([
                'x-lk-sanmark-janus-sessionkey' ,
            ])
            -> andReturn('the_key');

        $authSession = $this -> mock(AuthSession::class);

        $authSession -> id = 'the_id';
        $authSession -> key = 'the_key';
        $authSession -> user_id = 'the_user_id';

        $authSessionsHandler
            -> shouldReceive('getByKeyIfActiveAndExtendActiveTime')
            -> withArgs([
                'the_key' ,
            ])
            -> andReturn($authSession);

        $result = $authSessionsController -> validateAuthSession($request);

        $this -> assertInstanceOf(JsonResponse::class, $result);
        $this -> assertEquals(200, $result -> getStatusCode());
        $this -> assertEquals((object) [
            'data' => (object) [
                'id' => 'the_id' ,
                'key' => 'the_key' ,
                'user_id' => 'the_user_id' ,
            ] ,
        ], $result -> getData());
    }

    public function testValidate_HandlesRecordNotFoundException()
    {
        $authSessionsHandler = $this -> mock(AuthSessionsHandler::class);
        $authSessionsValidator = $this -> mock(AuthSessionsValidator::class);

        $authSessionsController = new AuthSessionsController($authSessionsHandler, $authSessionsValidator);

        $request = $this -> mock(Request::class);

        $request
            -> shouldReceive('header')
            -> withArgs([
                'x-lk-sanmark-janus-sessionkey' ,
            ])
            -> andReturn('the_key');

        $authSessionsHandler
            -> shouldReceive('getByKeyIfActiveAndExtendActiveTime')
            -> withArgs([
                'the_key' ,
            ])
            -> andThrow(RecordNotFoundException::class);

        $result = $authSessionsController -> validateAuthSession($request);

        $this -> assertInstanceOf(JsonResponse::class, $result);
        $this -> assertEquals(401, $result -> getStatusCode());
    }

    public function test_validate_handlesEmptySessionKeyHeader()
    {
        $authSessionsHandler = $this -> mock(AuthSessionsHandler::class);
        $authSessionsValidator = $this -> mock(AuthSessionsValidator::class);

        $authSessionsController = new AuthSessionsController($authSessionsHandler, $authSessionsValidator);

        $request = $this -> mock(Request::class);

        $request
            -> shouldReceive('header')
            -> withArgs([
                'x-lk-sanmark-janus-sessionkey' ,
            ])
            -> andReturnNull();

        $result = $authSessionsController -> validateAuthSession($request);

        $this -> assertInstanceOf(JsonResponse::class, $result);
        $this -> assertEquals(401, $result -> getStatusCode());
    }
}
