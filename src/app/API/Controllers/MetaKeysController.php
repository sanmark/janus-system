<?php

namespace App\API\Controllers;

use App\API\Responses\SuccessResponse;
use App\Handlers\MetaKeysHandler;

class MetaKeysController extends Base
{
    private $metaKeysHandler;

    public function __construct(
    MetaKeysHandler $metaKeysHandler
    ) {
        $this -> metaKeysHandler = $metaKeysHandler;
    }

    /**
     * @SWG\Get(
     *  path = "/metakeys",
     *  summary = "Get a list of MetaKeys.",
     *  security = {
     *   {
     *    "x-lk-sanmark-janus-app-key": {},
     *    "x-lk-sanmark-janus-app-secret-hash": {},
     *   },
     *  },
     *  @SWG\Response (
     *   response = 200,
     *   description = "An array of MetaKeys.",
     *   examples = {
     *    {
     *     "data": {
     *      {
     *       "id": "int",
     *       "key": "string",
     *       "created_at": "string",
     *       "updated_at": "string",
     *      },
     *     }
     *    }
     *   }
     *  ),
     * )
     */
    public function get()
    {
        $metaKeys = $this
            -> metaKeysHandler
            -> all();

        $response = new SuccessResponse($metaKeys);

        return $response -> getResponse();
    }
}
