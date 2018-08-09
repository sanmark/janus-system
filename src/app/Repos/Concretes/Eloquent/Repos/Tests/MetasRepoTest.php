<?php

namespace App\Repos\Concretes\Eloquent\Repos\Tests;

use App\Models\Meta;
use App\Repos\Concretes\Eloquent\Models\Meta as EMeta;
use App\Repos\Concretes\Eloquent\Repos\MetasRepo;
use App\Repos\Exceptions\RecordNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

/**
 * @codeCoverageIgnore
 */
class MetasRepoTest extends TestCase
{
    public function test_create_ok()
    {
        $eMeta = $this -> mock(EMeta::class);

        $metasRepo = new MetasRepo($eMeta);

        $eMeta -> shouldReceive('newInstance')
            -> withArgs([])
            -> andReturnSelf();

        $eMeta -> shouldReceive('setAttribute')
            -> withArgs([
                'user_id' ,
                149 ,
            ]);

        $eMeta -> shouldReceive('setAttribute')
            -> withArgs([
                'meta_key_id' ,
                150 ,
            ]);

        $eMeta -> shouldReceive('setAttribute')
            -> withArgs([
                'value' ,
                'the-value' ,
            ]);

        $eMeta -> shouldReceive('save')
            -> withArgs([]);

        $eMeta -> shouldReceive('getAttribute')
            -> withArgs([
                'id' ,
            ])
            -> andReturn(151);

        $eMeta -> shouldReceive('getAttribute')
            -> withArgs([
                'meta_key_id' ,
            ])
            -> andReturn(150);

        $eMeta -> shouldReceive('getAttribute')
            -> withArgs([
                'user_id' ,
            ])
            -> andReturn(149);

        $eMeta -> shouldReceive('getAttribute')
            -> withArgs([
                'value' ,
            ])
            -> andReturn('the-value');

        $eMeta -> shouldReceive('getAttribute')
            -> withArgs([
                'created_at' ,
            ])
            -> andReturn('the-created_at');

        $eMeta -> shouldReceive('getAttribute')
            -> withArgs([
                'updated_at' ,
            ])
            -> andReturn('the-updated_at');

        $response = $metasRepo -> create(149, 150, 'the-value');

        $expectedMeta = new Meta();
        $expectedMeta -> id = 151;
        $expectedMeta -> meta_key_id = 150;
        $expectedMeta -> user_id = 149;
        $expectedMeta -> value = 'the-value';
        $expectedMeta -> created_at = 'the-created_at';
        $expectedMeta -> updated_at = 'the-updated_at';

        $this -> assertEquals($expectedMeta, $response);
    }

    public function test_getAllByUserId_ok()
    {
        $eMeta = $this -> mock(EMeta::class);

        $metasRepo = new MetasRepo($eMeta);

        $eMeta -> shouldReceive('where')
            -> withArgs([
                'user_id' ,
                '=' ,
                149 ,
            ])
            -> andReturnSelf();

        $eMeta -> shouldReceive('get')
            -> withArgs([])
            -> andReturn([ $eMeta ]);

        $eMeta -> shouldReceive('getAttribute')
            -> withArgs([
                'id' ,
            ])
            -> andReturn(150);

        $eMeta -> shouldReceive('getAttribute')
            -> withArgs([
                'user_id' ,
            ])
            -> andReturn(149);

        $eMeta -> shouldReceive('getAttribute')
            -> withArgs([
                'meta_key_id' ,
            ])
            -> andReturn(151);

        $eMeta -> shouldReceive('getAttribute')
            -> withArgs([
                'value' ,
            ])
            -> andReturn('the-value');

        $eMeta -> shouldReceive('getAttribute')
            -> withArgs([
                'created_at' ,
            ])
            -> andReturn('the-created_at');

        $eMeta -> shouldReceive('getAttribute')
            -> withArgs([
                'updated_at' ,
            ])
            -> andReturn('the-updated_at');

        $response = $metasRepo -> getAllByUserId(149);

        $expectedMeta = new Meta();
        $expectedMeta -> id = 150;
        $expectedMeta -> meta_key_id = 151;
        $expectedMeta -> user_id = 149;
        $expectedMeta -> value = 'the-value';
        $expectedMeta -> created_at = 'the-created_at';
        $expectedMeta -> updated_at = 'the-updated_at';

        $this -> assertEquals([
            $expectedMeta ,
        ], $response);
    }

    public function test_getOneByUserIdAndMetaKey_ok()
    {
        $eMeta = $this -> mock(EMeta::class);

        $metasRepo = new MetasRepo($eMeta);

        $eMeta -> shouldReceive('whereHas')
            -> andReturnSelf();

        $eMeta -> shouldReceive('where')
            -> withArgs([
                'user_id' ,
                '=' ,
                149 ,
            ])
            -> andReturnSelf();

        $eMeta -> shouldReceive('firstOrFail')
            -> withArgs([])
            -> andReturnSelf();

        $eMeta -> shouldReceive('getAttribute')
            -> withArgs([
                'id' ,
            ])
            -> andReturn(150);

        $eMeta -> shouldReceive('getAttribute')
            -> withArgs([
                'user_id' ,
            ])
            -> andReturn(149);

        $eMeta -> shouldReceive('getAttribute')
            -> withArgs([
                'meta_key_id' ,
            ])
            -> andReturn(151);

        $eMeta -> shouldReceive('getAttribute')
            -> withArgs([
                'value' ,
            ])
            -> andReturn('the-value');

        $eMeta -> shouldReceive('getAttribute')
            -> withArgs([
                'created_at' ,
            ])
            -> andReturn('the-created_at');

        $eMeta -> shouldReceive('getAttribute')
            -> withArgs([
                'updated_at' ,
            ])
            -> andReturn('the-updated_at');

        $response = $metasRepo -> getOneByUserIdAndMetaKey(149, 'the-meta-key');

        $expectedMeta = new Meta();
        $expectedMeta -> id = 150;
        $expectedMeta -> meta_key_id = 151;
        $expectedMeta -> user_id = 149;
        $expectedMeta -> value = 'the-value';
        $expectedMeta -> created_at = 'the-created_at';
        $expectedMeta -> updated_at = 'the-updated_at';

        $this -> assertEquals($expectedMeta, $response);
    }

    public function test_getOneByUserIdAndMetaKey_throwsRecordNotFoundException()
    {
        $eMeta = $this -> mock(EMeta::class);

        $metasRepo = new MetasRepo($eMeta);

        $eMeta -> shouldReceive('whereHas')
            -> andReturnSelf();

        $eMeta -> shouldReceive('where')
            -> withArgs([
                'user_id' ,
                '=' ,
                149 ,
            ])
            -> andReturnSelf();

        $eMeta -> shouldReceive('firstOrFail')
            -> withArgs([])
            -> andThrow(ModelNotFoundException::class);

        $this -> expectException(RecordNotFoundException::class);

        $metasRepo -> getOneByUserIdAndMetaKey(149, 'the-meta-key');
    }

    public function test_update_ok()
    {
        $eMeta = $this -> mock(EMeta::class);

        $metasRepo = new MetasRepo($eMeta);

        $eMeta -> shouldReceive('findOrFail')
            -> withArgs([
                149 ,
            ])
            -> andReturnSelf();

        $eMeta -> shouldReceive('setAttribute')
            -> withArgs([
                'value' ,
                'the-value' ,
            ]);

        $eMeta -> shouldReceive('save')
            -> withArgs([]);

        $eMeta -> shouldReceive('getAttribute')
            -> withArgs([
                'id' ,
            ])
            -> andReturn(149);

        $eMeta -> shouldReceive('getAttribute')
            -> withArgs([
                'user_id' ,
            ])
            -> andReturn(150);

        $eMeta -> shouldReceive('getAttribute')
            -> withArgs([
                'meta_key_id' ,
            ])
            -> andReturn(151);

        $eMeta -> shouldReceive('getAttribute')
            -> withArgs([
                'value' ,
            ])
            -> andReturn('the-value');

        $eMeta -> shouldReceive('getAttribute')
            -> withArgs([
                'created_at' ,
            ])
            -> andReturn('the-created_at');

        $eMeta -> shouldReceive('getAttribute')
            -> withArgs([
                'updated_at' ,
            ])
            -> andReturn('the-updated_at');

        $response = $metasRepo -> update(149, 'the-value');

        $expectedMeta = new Meta();
        $expectedMeta -> id = 149;
        $expectedMeta -> meta_key_id = 151;
        $expectedMeta -> user_id = 150;
        $expectedMeta -> value = 'the-value';
        $expectedMeta -> created_at = 'the-created_at';
        $expectedMeta -> updated_at = 'the-updated_at';

        $this -> assertEquals($expectedMeta, $response);
    }

    public function test_update_throwsRecordNotFoundException()
    {
        $eMeta = $this -> mock(EMeta::class);

        $metasRepo = new MetasRepo($eMeta);

        $eMeta -> shouldReceive('findOrFail')
            -> withArgs([
                149 ,
            ])
            -> andThrow(ModelNotFoundException::class);

        $this -> expectException(RecordNotFoundException::class);

        $metasRepo -> update(149, 'the-value');
    }
}
