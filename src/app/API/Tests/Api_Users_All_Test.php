<?php

namespace App\API\Tests;

use Tests\TestCase;

/**
 * @codeCoverageIgnore
 */
class Api_Users_All_Test extends TestCase
{
    private $url;

    protected function setUp()
    {
        parent::setUp();

        $this->seedDb();
        $this->url = 'api/users';
    }

    public function testCorrectDataAreReturned()
    {
        $response1 = $this->getWithValidAppKeyAndSecretHash($this->url . '?page=1&count=1');
        $response2 = $this->getWithValidAppKeyAndSecretHash($this->url . '?page=2&count=1');
        $response3 = $this->getWithValidAppKeyAndSecretHash($this->url . '?page=3&count=1');

        $response1->assertStatus(200);
        $response1->assertJson([
            'data' => [
                [
                    'id' => '1',
                    'key' => 'user1',
                ],
            ],
        ]);

        $response2->assertStatus(200);
        $response2->assertJson([
            'data' => [
                [
                    'id' => '2',
                    'key' => 'user2',
                ],
            ],
        ]);

        $response3->assertStatus(200);
        $response3->assertJson([
            'data' => [
                [
                    'id' => '3',
                    'key' => 'user3',
                ],
            ],
        ]);
    }
}
