<?php

namespace App\Repos\Concretes\Eloquent\Repos\Tests;

use App\Models\FacebookAccount;
use App\Repos\Concretes\Eloquent\Models\FacebookAccount as EFacebookAccount;
use App\Repos\Concretes\Eloquent\Repos\FacebookAccountsRepo;
use App\Repos\Exceptions\RecordNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

/**
 * @codeCoverageIgnore
 */
class FacebookAccountsRepoTest extends TestCase
{
    public function test_create_ok()
    {
        $eFacebookAccount = $this -> mock(EFacebookAccount::class);

        $facebookAccountsRepo = new FacebookAccountsRepo($eFacebookAccount);

        $eFacebookAccount -> shouldReceive('newInstance')
            -> withArgs([])
            -> andReturnSelf();

        $eFacebookAccount -> shouldReceive('setAttribute')
            -> withArgs([
                'user_id' ,
                149 ,
            ]);

        $eFacebookAccount -> shouldReceive('setAttribute')
            -> withArgs([
                'key' ,
                'the-key' ,
            ]);

        $eFacebookAccount -> shouldReceive('save')
            -> withArgs([]);

        $eFacebookAccount -> shouldReceive('getAttribute')
            -> withArgs([
                'user_id' ,
            ])
            -> andReturn(149);

        $eFacebookAccount -> shouldReceive('getAttribute')
            -> withArgs([
                'key' ,
            ])
            -> andReturn('the-key');

        $eFacebookAccount -> shouldReceive('getAttribute')
            -> withArgs([
                'created_at' ,
            ])
            -> andReturn('the-created_at');

        $eFacebookAccount -> shouldReceive('getAttribute')
            -> withArgs([
                'updated_at' ,
            ])
            -> andReturn('the-updated_at');

        $response = $facebookAccountsRepo -> create(149, 'the-key');

        $expectedFaceboookAccount = new FacebookAccount();
        $expectedFaceboookAccount -> id = null;
        $expectedFaceboookAccount -> user_id = 149;
        $expectedFaceboookAccount -> key = 'the-key';
        $expectedFaceboookAccount -> created_at = 'the-created_at';
        $expectedFaceboookAccount -> updated_at = 'the-updated_at';

        $this -> assertEquals($expectedFaceboookAccount, $response);
    }

    public function test_getByKey_ok()
    {
        $eFacebookAccount = $this -> mock(EFacebookAccount::class);

        $facebookAccountsRepo = new FacebookAccountsRepo($eFacebookAccount);

        $eFacebookAccount -> shouldReceive('where')
            -> withArgs([
                'key' ,
                '=' ,
                'the-key' ,
            ])
            -> andReturnSelf();

        $eFacebookAccount -> shouldReceive('firstOrFail')
            -> withArgs([])
            -> andReturnSelf();

        $eFacebookAccount -> shouldReceive('getAttribute')
            -> withArgs([
                'id' ,
            ])
            -> andReturn(149);

        $eFacebookAccount -> shouldReceive('getAttribute')
            -> withArgs([
                'user_id' ,
            ])
            -> andReturn(150);

        $eFacebookAccount -> shouldReceive('getAttribute')
            -> withArgs([
                'key' ,
            ])
            -> andReturn('the-key');

        $eFacebookAccount -> shouldReceive('getAttribute')
            -> withArgs([
                'created_at' ,
            ])
            -> andReturn('the-created_at');

        $eFacebookAccount -> shouldReceive('getAttribute')
            -> withArgs([
                'updated_at' ,
            ])
            -> andReturn('the-updated_at');

        $response = $facebookAccountsRepo -> getByKey('the-key');

        $expectedFaceboookAccount = new FacebookAccount();
        $expectedFaceboookAccount -> id = 149;
        $expectedFaceboookAccount -> user_id = 150;
        $expectedFaceboookAccount -> key = 'the-key';
        $expectedFaceboookAccount -> created_at = 'the-created_at';
        $expectedFaceboookAccount -> updated_at = 'the-updated_at';

        $this -> assertEquals($expectedFaceboookAccount, $response);
    }

    public function test_getByKey_throwsRecordNotFoundException()
    {
        $eFacebookAccount = $this -> mock(EFacebookAccount::class);

        $facebookAccountsRepo = new FacebookAccountsRepo($eFacebookAccount);

        $eFacebookAccount -> shouldReceive('where')
            -> withArgs([
                'key' ,
                '=' ,
                'the-key' ,
            ])
            -> andReturnSelf();

        $eFacebookAccount -> shouldReceive('firstOrFail')
            -> withArgs([])
            -> andThrow(ModelNotFoundException::class);

        $this -> expectException(RecordNotFoundException::class);

        $facebookAccountsRepo -> getByKey('the-key');
    }
}
