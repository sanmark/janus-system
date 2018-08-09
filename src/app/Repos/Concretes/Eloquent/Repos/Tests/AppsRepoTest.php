<?php

namespace App\Repos\Concretes\Eloquent\Repos\Tests;

use App\Models\App;
use App\Repos\Concretes\Eloquent\Models\App as EApp;
use App\Repos\Concretes\Eloquent\Repos\AppsRepo;
use App\Repos\Exceptions\RecordNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

/**
 * @codeCoverageIgnore
 */
class AppsRepoTest extends TestCase
{
    public function test_getByKey_ok()
    {
        $eApp = $this -> mock(EApp::class);

        $appsRepo = new AppsRepo($eApp);

        $eApp -> shouldReceive('where')
            -> withArgs([
                'key' ,
                '=' ,
                'the-key' ,
            ])
            -> andReturnSelf();

        $theApp = $this -> mock(EApp::class);

        $theApp -> shouldReceive('getAttribute')
            -> withArgs([
                'id' ,
            ])
            -> andReturn(149);

        $theApp -> shouldReceive('getAttribute')
            -> withArgs([
                'key' ,
            ])
            -> andReturn('the-key');

        $theApp -> shouldReceive('getAttribute')
            -> withArgs([
                'secret' ,
            ])
            -> andReturn('the-secret');

        $theApp -> shouldReceive('getAttribute')
            -> withArgs([
                'created_at' ,
            ])
            -> andReturn('the-created_at');

        $theApp -> shouldReceive('getAttribute')
            -> withArgs([
                'updated_at' ,
            ])
            -> andReturn('the-updated_at');

        $eApp -> shouldReceive('firstOrFail')
            -> withArgs([])
            -> andReturn($theApp);

        $response = $appsRepo -> getByKey('the-key');

        $expectedApp = new App();
        $expectedApp -> id = 149;
        $expectedApp -> key = 'the-key';
        $expectedApp -> secret = 'the-secret';
        $expectedApp -> created_at = 'the-created_at';
        $expectedApp -> updated_at = 'the-updated_at';

        $this -> assertEquals($expectedApp, $response);
    }

    public function test_getByKey_throwsRecordNotFoundException()
    {
        $eApp = $this -> mock(EApp::class);

        $appsRepo = new AppsRepo($eApp);

        $eApp -> shouldReceive('where')
            -> withArgs([
                'key' ,
                '=' ,
                'the-key' ,
            ])
            -> andReturnSelf();

        $eApp -> shouldReceive('firstOrFail')
            -> withArgs([])
            -> andThrow(ModelNotFoundException::class);

        $this -> expectException(RecordNotFoundException::class);

        $appsRepo -> getByKey('the-key');
    }
}
