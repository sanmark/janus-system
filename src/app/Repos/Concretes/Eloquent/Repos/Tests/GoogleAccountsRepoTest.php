<?php

namespace App\Repos\Concretes\Eloquent\Repos\Tests;

use App\Models\GoogleAccount;
use App\Repos\Concretes\Eloquent\Models\GoogleAccount as EGoogleAccount;
use App\Repos\Concretes\Eloquent\Repos\GoogleAccountsRepo;
use App\Repos\Exceptions\RecordNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

/**
 * @codeCoverageIgnore
 */
class GoogleAccountsRepoTest extends TestCase
{
    public function test_create_ok()
    {
        $eGoogleAccount = $this -> mock(EGoogleAccount::class);

        $googleAccountsRepo = new GoogleAccountsRepo($eGoogleAccount);

        $eGoogleAccount -> shouldReceive('newInstance')
            -> withArgs([])
            -> andReturnSelf();

        $eGoogleAccount -> shouldReceive('setAttribute')
            -> withArgs([
                'user_id' ,
                149 ,
            ]);

        $eGoogleAccount -> shouldReceive('setAttribute')
            -> withArgs([
                'key' ,
                'the-key' ,
            ]);

        $eGoogleAccount -> shouldReceive('save')
            -> withArgs([]);

        $eGoogleAccount -> shouldReceive('getAttribute')
            -> withArgs([
                'user_id' ,
            ])
            -> andReturn('the-user_id');

        $eGoogleAccount -> shouldReceive('getAttribute')
            -> withArgs([
                'key' ,
            ])
            -> andReturn('the-key');

        $eGoogleAccount -> shouldReceive('getAttribute')
            -> withArgs([
                'created_at' ,
            ])
            -> andReturn('the-created_at');

        $eGoogleAccount -> shouldReceive('getAttribute')
            -> withArgs([
                'updated_at' ,
            ])
            -> andReturn('the-updated_at');

        $response = $googleAccountsRepo -> create(149, 'the-key');

        $expectedGoogleAccount = new GoogleAccount();
        $expectedGoogleAccount -> id = null;
        $expectedGoogleAccount -> user_id = 'the-user_id';
        $expectedGoogleAccount -> key = 'the-key';
        $expectedGoogleAccount -> created_at = 'the-created_at';
        $expectedGoogleAccount -> updated_at = 'the-updated_at';

        $this -> assertEquals($expectedGoogleAccount, $response);
    }

    public function test_get_ok()
    {
        $eGoogleAccount = $this -> mock(EGoogleAccount::class);

        $googleAccountsRepo = new GoogleAccountsRepo($eGoogleAccount);

        $eGoogleAccount -> shouldReceive('findOrFail')
            -> withArgs([
                149 ,
            ])
            -> andReturnSelf();

        $eGoogleAccount -> shouldReceive('getAttribute')
            -> withArgs([
                'id' ,
            ])
            -> andReturn(149);

        $eGoogleAccount -> shouldReceive('getAttribute')
            -> withArgs([
                'user_id' ,
            ])
            -> andReturn('the-user_id');

        $eGoogleAccount -> shouldReceive('getAttribute')
            -> withArgs([
                'key' ,
            ])
            -> andReturn('the-key');

        $eGoogleAccount -> shouldReceive('getAttribute')
            -> withArgs([
                'created_at' ,
            ])
            -> andReturn('the-created_at');

        $eGoogleAccount -> shouldReceive('getAttribute')
            -> withArgs([
                'updated_at' ,
            ])
            -> andReturn('the-updated_at');

        $response = $googleAccountsRepo -> get(149);

        $expectedGoogleAccount = new GoogleAccount();
        $expectedGoogleAccount -> id = 149;
        $expectedGoogleAccount -> user_id = 'the-user_id';
        $expectedGoogleAccount -> key = 'the-key';
        $expectedGoogleAccount -> created_at = 'the-created_at';
        $expectedGoogleAccount -> updated_at = 'the-updated_at';

        $this -> assertEquals($expectedGoogleAccount, $response);
    }

    public function test_get_throwsRecordNotFoundException()
    {
        $eGoogleAccount = $this -> mock(EGoogleAccount::class);

        $googleAccountsRepo = new GoogleAccountsRepo($eGoogleAccount);

        $eGoogleAccount -> shouldReceive('findOrFail')
            -> withArgs([
                149 ,
            ])
            -> andThrow(ModelNotFoundException::class);

        $this -> expectException(RecordNotFoundException::class);

        $googleAccountsRepo -> get(149);
    }

    public function test_getByKey_ok()
    {
        $eGoogleAccount = $this -> mock(EGoogleAccount::class);

        $googleAccountsRepo = new GoogleAccountsRepo($eGoogleAccount);

        $eGoogleAccount -> shouldReceive('where')
            -> withArgs([
                'key' ,
                '=' ,
                'the-key' ,
            ])
            -> andReturnSelf();

        $eGoogleAccount -> shouldReceive('firstOrFail')
            -> withArgs([])
            -> andReturnSelf();

        $eGoogleAccount -> shouldReceive('getAttribute')
            -> withArgs([
                'id' ,
            ])
            -> andReturn('the-id');

        $eGoogleAccount -> shouldReceive('getAttribute')
            -> withArgs([
                'user_id' ,
            ])
            -> andReturn('the-user_id');

        $eGoogleAccount -> shouldReceive('getAttribute')
            -> withArgs([
                'key' ,
            ])
            -> andReturn('the-key');

        $eGoogleAccount -> shouldReceive('getAttribute')
            -> withArgs([
                'created_at' ,
            ])
            -> andReturn('the-created_at');

        $eGoogleAccount -> shouldReceive('getAttribute')
            -> withArgs([
                'updated_at' ,
            ])
            -> andReturn('the-updated_at');

        $response = $googleAccountsRepo -> getByKey('the-key');

        $expectedGoogleAccount = new GoogleAccount();
        $expectedGoogleAccount -> id = 'the-id';
        $expectedGoogleAccount -> user_id = 'the-user_id';
        $expectedGoogleAccount -> key = 'the-key';
        $expectedGoogleAccount -> created_at = 'the-created_at';
        $expectedGoogleAccount -> updated_at = 'the-updated_at';

        $this -> assertEquals($expectedGoogleAccount, $response);
    }

    public function test_getByKey_throwsRecordNotFoundException()
    {
        $eGoogleAccount = $this -> mock(EGoogleAccount::class);

        $googleAccountsRepo = new GoogleAccountsRepo($eGoogleAccount);

        $eGoogleAccount -> shouldReceive('where')
            -> withArgs([
                'key' ,
                '=' ,
                'the-key' ,
            ])
            -> andReturnSelf();

        $eGoogleAccount -> shouldReceive('firstOrFail')
            -> withArgs([])
            -> andThrow(ModelNotFoundException::class);

        $this -> expectException(RecordNotFoundException::class);

        $googleAccountsRepo -> getByKey('the-key');
    }
}
