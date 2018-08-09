<?php

namespace App\API\Controllers;

use App\API\Responses\SuccessResponse;
use App\Handlers\MetaKeysHandler;
use App\Http\Controllers\Controller;

class MetasController extends Controller
{
    private $metaKeysHandler;

    public function __construct(
    MetaKeysHandler $metaKeysHandler
    ) {
        $this -> metaKeysHandler = $metaKeysHandler;
    }

    public function metaValueUsersGet(string $metaKey, string $metaValue)
    {
        $users = $this
            -> metaKeysHandler
            -> getUsersForMetaValue($metaKey, $metaValue);

        $response = new SuccessResponse($users);

        return $response -> getResponse();
    }
}
