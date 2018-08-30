<?php

namespace App\API\Controllers;

use App\API\Constants\Headers\RequestHeaderConstants;
use App\API\Constants\Inputs\UsersInputConstants;
use App\API\Responses\ErrorResponse;
use App\API\Responses\SuccessResponse;
use App\API\Validators\Contracts\IAuthSessionsValidator;
use App\API\Validators\Exceptions\InvalidInputException;
use App\Handlers\AuthSessionsHandler;
use App\Repos\Exceptions\RecordNotFoundException;
use Illuminate\Http\Request;
use function response;

class AuthSessionsController extends Base
{
    private $authSessionsHandler;
    private $authSessionsValidator;

    public function __construct(
    AuthSessionsHandler $authSessionsHandler,
        IAuthSessionsValidator $authSessionsValidator
    ) {
        $this -> authSessionsHandler = $authSessionsHandler;
        $this -> authSessionsValidator = $authSessionsValidator;
    }

    /**
     * @SWG\Post(
     *  path = "/auth-sessions",
     *  summary = "Create a new AuthSession.",
     *  security = {
     *   {
     *    "x-lk-sanmark-janus-app-key": {},
     *    "x-lk-sanmark-janus-app-secret-hash": {},
     *   },
     *  },
     *  @SWG\Parameter (
     *   name = "user_key",
     *   in = "formData",
     *   type = "string",
     *   required = true,
     *  ),
     *  @SWG\Parameter (
     *   name = "user_secret",
     *   in = "formData",
     *   type = "string",
     *   required = true,
     *  ),
     *  @SWG\Response (
     *   response = 200,
     *   description = "AuthSession.",
     *   examples = {
     *    {
     *     "data": {
     *      {
     *       "id": "int",
     *       "key": "string",
     *       "user_id": "int",
     *      },
     *     }
     *    }
     *   }
     *  ),
     *  @SWG\Response (
     *   response = 401,
     *   description = "Either provided App Key andor App Secret Hash or provided user credentials are invalid.",
     *  ),
     * )
     */
    public function create(Request $request)
    {
        try {
            $data = $request -> toArray();

            $this
                -> authSessionsValidator
                -> create($data);

            $userKey = $request -> get(UsersInputConstants::UserKey);
            $userSecret = $request -> get(UsersInputConstants::UserSecret);

            $authSession = $this
                -> authSessionsHandler
                -> create($userKey, $userSecret);

            $response = new SuccessResponse($authSession);

            return response()
                    -> json($response -> getOutput())
                    -> setStatusCode(201);
        } catch (InvalidInputException $ex) {
            $response = new ErrorResponse($ex -> getErrors());

            return response()
                    -> json($response -> getOutput())
                    -> setStatusCode(400);
        } catch (RecordNotFoundException $ex) {
            return response()
                    -> json()
                    -> setStatusCode(401);
        }
    }

    /**
     * @SWG\Get(
     *  path = "/auth-sessions/validate",
     *  summary = "Validate an AuthSession.",
     *  security = {
     *   {
     *    "x-lk-sanmark-janus-app-key": {},
     *    "x-lk-sanmark-janus-app-secret-hash": {},
     *   },
     *  },
     *  @SWG\Parameter (
     *   name = "x-lk-sanmark-janus-sessionkey",
     *   in = "header",
     *   required = true,
     *   type = "string",
     *  ),
     *  @SWG\Response (
     *   response = 200,
     *   description = "AuthSession.",
     *   examples = {
     *    {
     *     "data": {
     *      {
     *       "id": "int",
     *       "key": "string",
     *       "user_id": "int",
     *       "created_at": "string",
     *       "updated_at": "string",
     *      },
     *     }
     *    }
     *   }
     *  ),
     *  @SWG\Response (
     *   response = 401,
     *   description = "Either provided App Key andor App Secret Hash or provided SessionKey are invalid.",
     *  ),
     * )
     */
    public function validateAuthSession(Request $request)
    {
        try {
            $key = $request -> header(RequestHeaderConstants::SESSION_KEY);

            if (is_null($key)) {
                return response()
                        -> json()
                        -> setStatusCode(401);
            }

            $authSession = $this
                -> authSessionsHandler
                -> getByKeyIfActiveAndExtendActiveTime($key);

            $response = new SuccessResponse($authSession);

            return response()
                    -> json($response -> getOutput())
                    -> setStatusCode(200);
        } catch (RecordNotFoundException $ex) {
            return response()
                    -> json()
                    -> setStatusCode(401);
        }
    }
}
