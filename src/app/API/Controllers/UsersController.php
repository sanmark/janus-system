<?php

namespace App\API\Controllers;

use App\API\Constants\Inputs\UsersInputConstants;
use App\API\Responses\ErrorResponse;
use App\API\Responses\SuccessResponse;
use App\API\Validators\Constants\ResponseConstants;
use App\API\Validators\Contracts\IUsersValidator;
use App\API\Validators\Exceptions\InvalidInputException;
use App\Handlers\MetasHandler;
use App\Handlers\UserSecretResetRequestsHandler;
use App\Handlers\UsersHandler;
use App\Repos\Exceptions\RecordNotFoundException;
use App\Repos\Exceptions\UniqueConstraintFailureException;
use Illuminate\Http\Request;
use function response;

class UsersController extends Base
{
    private $metasHandler;
    private $usersHandler;
    private $userSecretResetRequestsHandler;
    private $usersValidator;

    public function __construct(
        MetasHandler $metasHandler,
        UsersHandler $usersHandler,
        UserSecretResetRequestsHandler $userSecretResetRequestsHandler,
        IUsersValidator $usersValidator
    ) {
        $this->metasHandler = $metasHandler;
        $this->usersHandler = $usersHandler;
        $this->userSecretResetRequestsHandler = $userSecretResetRequestsHandler;
        $this->usersValidator = $usersValidator;
    }

    /**
     * @SWG\Get(
     *  path = "/users",
     *  summary = "Get a paginated list of Users.",
     *  security = {
     *   {
     *    "x-lk-sanmark-janus-app-key": {},
     *    "x-lk-sanmark-janus-app-secret-hash": {},
     *   },
     *  },
     *  @SWG\Parameter (
     *   name = "page",
     *   in = "query",
     *   type = "integer",
     *  ),
     *  @SWG\Parameter (
     *   name = "count",
     *   in = "query",
     *   type = "integer",
     *  ),
     *  @SWG\Parameter (
     *   name = "no_pagination",
     *   in = "query",
     *   type = "boolean",
     *  ),
     *  @SWG\Parameter (
     *   name = "order_by",
     *   in = "query",
     *   type = "string",
     *  ),
     *  @SWG\Parameter (
     *   name = "order_sort",
     *   in = "query",
     *   type = "string",
     *   description = "Possible values: `asc` and `desc`.",
     *  ),
     *  @SWG\Parameter (
     *   name = "meta_order_by",
     *   in = "query",
     *   type = "string",
     *   description = "Order by Meta.",
     *  ),
     *  @SWG\Parameter (
     *   name = "meta_order_sort",
     *   in = "query",
     *   type = "string",
     *   description = "Possible values: `asc` and `desc`.",
     *  ),
     *  @SWG\Parameter (
     *   name = "filters",
     *   in = "query",
     *   type = "string",
     *   description = "Possible values: [['id', [149,150,151]]].",
     *  ),
     *  @SWG\Response (
     *   response = 200,
     *   description = "Array of Users.",
     *   examples = {
     *    {
     *     "data": {
     *      {
     *       "id": "int",
     *       "key": "string",
     *      },
     *     },
     *    },
     *   },
     *  ),
     * )
     */
    public function all(Request $request)
    {
        try {
            $page = $request->get('page', 1);
            $count = $request->get('count', 10);
            $noPagination = $request->get('no_pagination', false);
            $orderBy = $request->get('order_by', 'id');
            $orderSort = $request->get('order_sort', 'asc');
            $metaOrderBy = $request->get('meta_order_by');
            $metaOrderSort = $request->get('meta_order_sort', 'asc');
            $withMetas = json_decode($request->get('with_metas', '[]'));
            $filters = json_decode($request->get('filters', '[]'));

            $users = $this
                ->usersHandler
                ->all($noPagination, $page, $count, $orderBy, $orderSort, $metaOrderBy, $metaOrderSort, $withMetas, $filters)
            ;

            $payload = [];

            foreach ($users as $user) {
                $payloadUser = [];

                $payloadUser['id'] = $user->id;
                $payloadUser['key'] = $user->key;

                foreach ($withMetas as $withMeta) {
                    if (property_exists($user, $withMeta)) {
                        $payloadUser[$withMeta] = $user->{$withMeta};
                    }
                }

                $payload[] = $payloadUser;
            }

            $response = new SuccessResponse($payload);

            return $response->getResponse();
        } catch (InvalidInputException $ex) {
            $response = new ErrorResponse($ex->getErrors());

            return
                response()
                ->json($response->getOutput())
                ->setStatusCode(422)
            ;
        }
    }

    /**
     * @SWG\Get(
     *  path = "/users/by-key/{key}",
     *  summary = "Get User by key.",
     *  security = {
     *   {
     *    "x-lk-sanmark-janus-app-key": {},
     *    "x-lk-sanmark-janus-app-secret-hash": {},
     *   },
     *  },
     *  @SWG\Parameter (
     *   name = "key",
     *   in = "path",
     *   type = "string",
     *  ),
     *  @SWG\Response (
     *   response = 200,
     *   description = "User object.",
     *   examples = {
     *    {
     *     "data": {
     *      "id": "int",
     *      "key": "string",
     *     },
     *    },
     *   },
     *  ),
     *  @SWG\Response (
     *   response = 404,
     *   description = "User not found.",
     *   examples = {
     *    {
     *     "errors": {
     *     },
     *    },
     *   },
     *  ),
     * )
     */
    public function byKeyGet(Request $request, string $key)
    {
        try {
            $user = $this
                ->usersHandler
                ->getByKey($key);

            $response = new SuccessResponse($user->toArrayOnly([
                'id',
                'key',
            ]));

            return $response->getResponse();
        } catch (RecordNotFoundException $ex) {
            $response = new ErrorResponse([], 404);

            return $response->getResponse();
        }
    }

    /**
     * @SWG\Post(
     *  path = "/users",
     *  summary = "Create User.",
     *  security = {
     *   {
     *    "x-lk-sanmark-janus-app-key": {},
     *    "x-lk-sanmark-janus-app-secret-hash": {},
     *   },
     *  },
     *  @SWG\Parameter (
     *   name = "user_key",
     *   in = "formData",
     *   required = true,
     *   type = "string",
     *  ),
     *  @SWG\Parameter (
     *   name = "user_secret",
     *   in = "formData",
     *   required = true,
     *   type = "string",
     *  ),
     *  @SWG\Response (
     *   response = 200,
     *   description = "User creation successful.",
     *   examples = {
     *    {
     *     "data": {
     *      {
     *       "id": "int",
     *       "key": "string",
     *      },
     *     },
     *    },
     *   },
     *  ),
     *  @SWG\Response (
     *   response = 400,
     *   description = "Validation failed.",
     *   examples = {
     *    {
     *     "errors": {
     *      "user_key": {
     *       "required",
     *      },
     *      "user_secret": {
     *       "required",
     *      },
     *     },
     *    },
     *   },
     *  ),
     * )
     */
    public function create(Request $request)
    {
        try {
            $data = $request->toArray();

            $this
                ->usersValidator
                ->create($data);

            $userKey = $request->get(UsersInputConstants::UserKey);
            $userSecret = $request->get(UsersInputConstants::UserSecret);

            $user = $this
                ->usersHandler
                ->create($userKey, $userSecret);

            $response = new SuccessResponse($user->toArrayOnly([
                'id',
                'key',
            ]));

            return response()
                    ->json($response->getOutput())
                    ->setStatusCode(201);
        } catch (InvalidInputException $ex) {
            $response = new ErrorResponse($ex->getErrors());

            return response()
                    ->json($response->getOutput())
                    ->setStatusCode(400);
        } catch (UniqueConstraintFailureException $ex) {
            $response = new ErrorResponse([
                $ex->getConstraint() => ResponseConstants::Duplicate,
            ]);

            return response()
                    ->json($response->getOutput())
                    ->setStatusCode(409);
        }
    }

    /**
     * @SWG\Get(
     *  path = "/users/{id}",
     *  summary = "Get User by ID.",
     *  security = {
     *   {
     *    "x-lk-sanmark-janus-app-key": {},
     *    "x-lk-sanmark-janus-app-secret-hash": {},
     *   },
     *  },
     *  @SWG\Parameter (
     *   name = "id",
     *   in = "path",
     *   type = "integer",
     *  ),
     *  @SWG\Response (
     *   response = 200,
     *   description = "User object.",
     *   examples = {
     *    {
     *     "data": {
     *      "id": "int",
     *      "key": "string",
     *     },
     *    },
     *   },
     *  ),
     *  @SWG\Response (
     *   response = 404,
     *   description = "User not found.",
     *   examples = {
     *    {
     *     "errors": {
     *     },
     *    },
     *   },
     *  ),
     * )
     */
    public function get(string $id)
    {
        try {
            $user = $this
                ->usersHandler
                ->get($id);

            $response = new SuccessResponse($user->toArrayOnly([
                'id',
                'key',
            ]));

            return $response->getResponse();
        } catch (RecordNotFoundException $ex) {
            $response = new ErrorResponse([], 404);

            return $response->getResponse();
        }
    }

    /**
     * @SWG\Patch(
     *  path = "/users/{id}",
     *  summary = "Update User.",
     *  security = {
     *   {
     *    "x-lk-sanmark-janus-app-key": {},
     *    "x-lk-sanmark-janus-app-secret-hash": {},
     *   },
     *  },
     *  @SWG\Parameter (
     *   name = "id",
     *   in = "path",
     *   type = "integer",
     *  ),
     *  @SWG\Parameter (
     *   name = "user_secret",
     *   in = "formData",
     *   required = true,
     *   type = "string",
     *  ),
     *  @SWG\Response (
     *   response = 200,
     *   description = "User object.",
     *   examples = {
     *    {
     *     "data": {
     *      "id": "int",
     *      "key": "string",
     *     },
     *    },
     *   },
     *  ),
     *  @SWG\Response (
     *   response = 400,
     *   description = "Validation failed.",
     *   examples = {
     *    {
     *     "errors": {
     *      "user_secret": {
     *       "required",
     *      },
     *     },
     *    },
     *   },
     *  ),
     *  @SWG\Response (
     *   response = 404,
     *   description = "User not found.",
     *   examples = {
     *    {
     *     "errors": {
     *     },
     *    },
     *   },
     *  ),
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $user = $this
                ->usersHandler
                ->update($id, $request->all([
                    UsersInputConstants::UserSecret,
                ]));

            $response = new SuccessResponse($user->toArrayOnly([
                'id',
                'key',
            ]));

            return response()
                    ->json($response->getOutput())
                    ->setStatusCode(200);
        } catch (RecordNotFoundException $ex) {
            $response = new ErrorResponse([], 404);

            return $response->getResponse();
        }
    }

    /**
     * @SWG\Get(
     *  path = "/users/{id}/metas",
     *  summary = "Metas of a User.",
     *  security = {
     *   {
     *    "x-lk-sanmark-janus-app-key": {},
     *    "x-lk-sanmark-janus-app-secret-hash": {},
     *   },
     *  },
     *  @SWG\Parameter (
     *   name = "id",
     *   in = "path",
     *   type = "integer",
     *   required = true,
     *  ),
     *  @SWG\Response (
     *   response = 200,
     *   description = "Array of Metas.",
     *   examples = {
     *    {
     *     "data": {
     *      {
     *       "meta_key": "string",
     *       "value": "string",
     *       "user_id": "int",
     *      },
     *     },
     *    },
     *   },
     *  ),
     *  @SWG\Response (
     *   response = 404,
     *   description = "User not found.",
     *   examples = {
     *    {
     *     "errors": {
     *     },
     *    },
     *   },
     *  ),
     * )
     */
    public function metasAll(int $userId)
    {
        try {
            $metas = $this
                ->metasHandler
                ->getAllByUserId($userId);

            $metasModified = [];
            foreach ($metas as $meta) {
                $metaModified = [];

                $metaModified['meta_key'] = $meta->getMetaKey();
                $metaModified['value'] = $meta->value;
                $metaModified['user_id'] = $meta->user_id;

                $metasModified[] = $metaModified;
            }

            $response = new SuccessResponse($metasModified);

            return $response->getResponse();
        } catch (RecordNotFoundException $ex) {
            $response = new ErrorResponse([], 404);

            return $response->getResponse();
        }
    }

    /**
     * @SWG\Get(
     *  path = "/users/{id}/metas/{key}",
     *  summary = "Get a User's Meta by key.",
     *  security = {
     *   {
     *    "x-lk-sanmark-janus-app-key": {},
     *    "x-lk-sanmark-janus-app-secret-hash": {},
     *   },
     *  },
     *  @SWG\Parameter (
     *   name = "id",
     *   in = "path",
     *   type = "string",
     *  ),
     *  @SWG\Parameter (
     *   name = "key",
     *   in = "path",
     *   type = "string",
     *  ),
     *  @SWG\Response (
     *   response = 200,
     *   description = "Meta object.",
     *   examples = {
     *    {
     *     "data": {
     *      "meta_key": "string",
     *      "value": "string",
     *      "user_id": "string",
     *     },
     *    },
     *   },
     *  ),
     *  @SWG\Response (
     *   response = 404,
     *   description = "User or MetaKey not found.",
     *   examples = {
     *    {
     *     "errors": {
     *     },
     *    },
     *   },
     *  ),
     * )
     */
    public function metasOne(int $userId, string $metaKey)
    {
        try {
            $meta = $this
                ->metasHandler
                ->getOneByUserIdAndMetaKeyKey($userId, $metaKey);

            $metaModified = [];
            $metaModified['meta_key'] = $meta->getMetaKey();
            $metaModified['value'] = $meta->value;
            $metaModified['user_id'] = $meta->user_id;

            $response = new SuccessResponse($metaModified);

            return $response->getResponse();
        } catch (RecordNotFoundException $ex) {
            $response = new ErrorResponse([], 404);

            return $response->getResponse();
        }
    }

    /**
     * @SWG\Patch(
     *  path = "/users/{id}/metas/{key}",
     *  summary = "Update a User's Meta.",
     *  security = {
     *   {
     *    "x-lk-sanmark-janus-app-key": {},
     *    "x-lk-sanmark-janus-app-secret-hash": {},
     *   },
     *  },
     *  @SWG\Parameter (
     *   name = "id",
     *   in = "path",
     *   type = "integer",
     *  ),
     *  @SWG\Parameter (
     *   name = "key",
     *   in = "path",
     *   type = "string",
     *  ),
     *  @SWG\Parameter (
     *   name = "value",
     *   in = "formData",
     *   required = true,
     *   type = "string",
     *  ),
     *  @SWG\Response (
     *   response = 200,
     *   description = "Meta object.",
     *   examples = {
     *    {
     *     "data": {
     *      "id": "int",
     *      "meta_key_id": "int",
     *      "user_id": "int",
     *      "value": "string",
     *     },
     *    },
     *   },
     *  ),
     *  @SWG\Response (
     *   response = 400,
     *   description = "Validation failed.",
     *   examples = {
     *    {
     *     "errors": {
     *      "value": {
     *       "required",
     *      },
     *     },
     *    },
     *   },
     *  ),
     *  @SWG\Response (
     *   response = 404,
     *   description = "User or MetaKey not found.",
     *   examples = {
     *    {
     *     "errors": {
     *     },
     *    },
     *   },
     *  ),
     * )
     */
    public function metasUpdate(Request $request, int $userId, string $metaKey)
    {
        try {
            $value = $request->get(UsersInputConstants::Value);

            $meta = $this
                ->metasHandler
                ->createByUserIdAndMetaKeyKey($userId, $metaKey, $value);

            $response = new SuccessResponse($meta);

            return $response->getResponse();
        } catch (InvalidInputException $ex) {
            $response = new ErrorResponse($ex->getErrors(), 400);

            return $response->getResponse();
        } catch (RecordNotFoundException $ex) {
            $response = new ErrorResponse([], 404);

            return $response->getResponse();
        }
    }

    /**
     * @SWG\Post(
     *  path = "/users/{id}/user-secret-reset-requests",
     *  summary = "Create UserSecretResetRequest.",
     *  security = {
     *   {
     *    "x-lk-sanmark-janus-app-key": {},
     *    "x-lk-sanmark-janus-app-secret-hash": {},
     *   },
     *  },
     *  @SWG\Parameter (
     *   name = "id",
     *   in = "path",
     *   required = true,
     *   type = "integer",
     *  ),
     *  @SWG\Response (
     *   response = 201,
     *   description = "UserSecretResetRequest creation successful.",
     *   examples = {
     *    {
     *     "data": {
     *      {
     *       "id": "int",
     *       "user_id": "string",
     *       "token": "string",
     *      },
     *     },
     *    },
     *   },
     *  ),
     *  @SWG\Response (
     *   response = 404,
     *   description = "User not found.",
     *   examples = {
     *    {
     *     "errors": {
     *      {
     *      },
     *     },
     *    },
     *   },
     *  ),
     * )
     */
    public function userSecretResetRequestsCreate(Request $request, int $userId)
    {
        try {
            $userSecretResetRequest = $this
                ->userSecretResetRequestsHandler
                ->create($userId);

            $response = new SuccessResponse($userSecretResetRequest->toArray(), 201);

            return $response->getResponse();
        } catch (RecordNotFoundException $ex) {
            $response = new ErrorResponse([], 404);

            return $response->getResponse();
        }
    }

    /**
     * @SWG\Post(
     *  path = "/users/{id}/user-secret-reset-requests/execute",
     *  summary = "Execute a UserSecretResetRequest.",
     *  security = {
     *   {
     *    "x-lk-sanmark-janus-app-key": {},
     *    "x-lk-sanmark-janus-app-secret-hash": {},
     *   },
     *  },
     *  @SWG\Parameter (
     *   name = "id",
     *   in = "path",
     *   required = true,
     *   type = "integer",
     *  ),
     *  @SWG\Parameter (
     *   name = "new_secret",
     *   in = "formData",
     *   required = true,
     *   type = "string",
     *  ),
     *  @SWG\Parameter (
     *   name = "user_secret_reset_request_token",
     *   in = "formData",
     *   required = true,
     *   type = "string",
     *  ),
     *  @SWG\Response (
     *   response = 200,
     *   description = "UserSecretResetRequest execution successful.",
     *   examples = {
     *    {
     *     "data": {
     *      {
     *       "id": "int",
     *       "key": "string",
     *      },
     *     },
     *    },
     *   },
     *  ),
     *  @SWG\Response (
     *   response = 400,
     *   description = "Validation error.",
     *   examples = {
     *    {
     *     "errors": {
     *      {
     *       "new_secret": {
     *        "required",
     *       },
     *       "user_secret_reset_request_token": {
     *        "required",
     *        "not_exists",
     *       },
     *      },
     *     },
     *    },
     *   },
     *  ),
     * )
     */
    public function userSecretResetRequestsExecute(Request $request, int $userId)
    {
        try {
            $data = $request->toArray();

            $this
                ->usersValidator
                ->userSecretResetRequestsExecute($data);

            $newSecret = $request->get(UsersInputConstants::NewSecret);
            $userSecretResetRequestToken = $request->get(UsersInputConstants::UserSecretResetRequestToken);

            $user = $this
                ->userSecretResetRequestsHandler
                ->execute($userId, $userSecretResetRequestToken, $newSecret);

            $response = new SuccessResponse($user->toArrayOnly([
                'id',
                'key',
            ]));

            return $response->getResponse();
        } catch (InvalidInputException $ex) {
            $response = new ErrorResponse($ex->getErrors(), 400);

            return $response->getResponse();
        } catch (RecordNotFoundException $ex) {
            $response = new ErrorResponse(
                [
                    UsersInputConstants::UserSecretResetRequestToken => [
                        ResponseConstants::NotExists,
                    ],
                ],
                400
            );

            return $response->getResponse();
        }
    }
}
