<?php

namespace App\API\Controllers;

use App\API\Responses\SuccessResponse;
use App\Handlers\MetaKeysHandler;

class MetasController extends Base
{
    private $metaKeysHandler;

    public function __construct(
    MetaKeysHandler $metaKeysHandler
    ) {
        $this -> metaKeysHandler = $metaKeysHandler;
    }

    /**
     * @SWG\Get(
     *  path = "/metas/{key}/value/{value}/users",
     *  summary = "Get a list of Users with a given value for a given MetaKey.",
     *  security = {
     *   {
     *    "x-lk-sanmark-janus-app-key": {},
     *    "x-lk-sanmark-janus-app-secret-hash": {},
     *   },
     *  },
     *  @SWG\Parameter (
     *   name = "key",
     *   in = "path",
     *   required = true,
     *   type = "string",
     *  ),
     *  @SWG\Parameter (
     *   name = "value",
     *   in = "path",
     *   required = true,
     *   type = "string",
     *  ),
     *  @SWG\Response (
     *   response = 200,
     *   description = "An array of Users.",
     *   examples = {
     *    {
     *     "data": {
     *      {
     *       "id": "int",
     *       "key": "string",
     *      },
     *     }
     *    }
     *   }
     *  ),
     * )
     */
    public function metaValueUsersGet(string $metaKey, string $metaValue)
    {
        $users = $this
            -> metaKeysHandler
            -> getUsersForMetaValue($metaKey, $metaValue);

        $response = new SuccessResponse($users);

        return $response -> getResponse();
    }
}
