<?php

namespace App\Handlers\Tests;

use App\Handlers\AppsHandler;
use App\Models\App;
use App\Repos\Contracts\IAppsRepo;
use Illuminate\Contracts\Hashing\Hasher;
use Tests\TestCase;

class AppsHandlerTest extends TestCase
{
    public function test_isValidByKeyAndSecretHash_ok()
    {
        $hasher = $this -> mock(Hasher::class);
        $appsRepo = $this -> mock(IAppsRepo::class);
        $app = $this -> mock(App::class);

        $appsHandler = new AppsHandler($hasher, $appsRepo);

        $app -> secret = 'the-secret';

        $appsRepo -> shouldReceive('getByKey')
            -> withArgs([
                'key' ,
            ])
            -> andReturn($app);

        $hasher -> shouldReceive('check')
            -> withArgs([
                'the-secret' ,
                'secret-hash' ,
            ])
            -> andReturn(true);

        $response = $appsHandler -> isValidByKeyAndSecretHash('key', 'secret-hash');

        $this -> assertTrue($response);
    }
}
