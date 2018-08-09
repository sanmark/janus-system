<?php

namespace App\API\Controllers;

use App\API\Responses\SuccessResponse;
use App\Handlers\MetaKeysHandler;
use Illuminate\Routing\Controller;

class MetaKeysController extends Controller
{
    private $metaKeysHandler;

    public function __construct(
    MetaKeysHandler $metaKeysHandler
    ) {
        $this -> metaKeysHandler = $metaKeysHandler;
    }

    public function get()
    {
        $metaKeys = $this
            -> metaKeysHandler
            -> all();

        $response = new SuccessResponse($metaKeys);

        return $response -> getResponse();
    }
}
