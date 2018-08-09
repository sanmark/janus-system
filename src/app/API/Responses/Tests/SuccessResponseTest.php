<?php

namespace App\API\Responses\Tests;

use App\API\Responses\SuccessResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tests\TestCase;

/**
 * @codeCoverageIgnore
 */
class SuccessResponseTest extends TestCase
{
    public function test_getOutput_ok()
    {
        $errorResponse = new SuccessResponse(149);

        $response = $errorResponse -> getOutput();

        $this -> assertSame([
            'data' => 149 ,
        ], $response);
    }

    public function test_getResponse_ok()
    {
        $errorResponse = new SuccessResponse(149, 150);

        $response = $errorResponse -> getResponse();

        $this -> assertInstanceOf(JsonResponse::class, $response);
        $this -> assertEquals(150, $response -> getStatusCode());
        $this -> assertEquals((object) [
            'data' => 149 ,
        ], $response -> getData());
    }
}
