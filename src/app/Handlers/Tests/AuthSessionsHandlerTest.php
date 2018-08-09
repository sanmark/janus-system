<?php

namespace App\Handlers\Tests;

use App\Handlers\AuthSessionsHandler;
use App\Handlers\UsersHandler;
use App\Models\AuthSession;
use App\Models\User;
use App\Repos\Contracts\IAuthSessionsRepo;
use App\Repos\Exceptions\RecordNotFoundException;
use App\SystemSettings\Contracts\ISystemSettingsInterface;
use Carbon\Carbon;
use Mockery;
use Tests\TestCase;

/**
 * @codeCoverageIgnore
 */
class AuthSessionsHandlerTest extends TestCase
{
    public function test_create_ok()
    {
        $mockUser = Mockery::mock(User::class);
        $mockUsersHandler = Mockery::mock(UsersHandler::class);
        $mockIAuthSessionsRepo = Mockery::mock(IAuthSessionsRepo::class);
        $mockAuthSession = Mockery::mock(AuthSession::class);
        $mockSystemSettingsInterface = $this -> mock(ISystemSettingsInterface::class);
        $mockCarbon = $this -> mock(Carbon::class);

        $mockUser -> id = 149;

        $mockUsersHandler
            -> shouldReceive('getUserIfCredentialsValid')
            -> withArgs([
                'the_key' ,
                'the_secret' ,
            ])
            -> andReturn($mockUser);

        $mockIAuthSessionsRepo
            -> shouldReceive('create')
            -> withArgs([ 149 ])
            -> andReturn($mockAuthSession);

        $authSessionsHandler = new AuthSessionsHandler($mockIAuthSessionsRepo, $mockSystemSettingsInterface, $mockUsersHandler, $mockCarbon);

        $response = $authSessionsHandler -> create('the_key', 'the_secret');

        $this -> assertSame($mockAuthSession, $response);
    }

    public function test_extendActiveTime_ok()
    {
        $authSessionsRepo = $this -> mock(IAuthSessionsRepo::class);
        $systemSettingsInterface = $this -> mock(ISystemSettingsInterface::class);
        $usersHandler = $this -> mock(UsersHandler::class);
        $carbon = $this -> mock(Carbon::class);

        $carbon -> shouldReceive('now')
            -> withArgs([])
            -> andReturn(149);

        $authSessionsHandler = new AuthSessionsHandler($authSessionsRepo, $systemSettingsInterface, $usersHandler, $carbon);

        $authSession = $this -> mock(AuthSession::class);

        $authSessionsRepo
            -> shouldReceive('update')
            -> withArgs([
                $authSession ,
            ])
            -> andReturn($authSession);

        $result = $authSessionsHandler -> extendActiveTime($authSession);

        $this -> assertSame($authSession, $result);
        $this -> assertSame(149, $authSession -> updated_at);
    }

    public function test_getByKey_ok()
    {
        $usersHandler = Mockery::mock(UsersHandler::class);
        $authSessionsRepo = Mockery::mock(IAuthSessionsRepo::class);
        $systemSettingsInterface = $this -> mock(ISystemSettingsInterface::class);
        $carbon = $this -> mock(Carbon::class);

        $authSessionsHandler = new AuthSessionsHandler($authSessionsRepo, $systemSettingsInterface, $usersHandler, $carbon);

        $authSession = $this -> mock(AuthSession::class);

        $authSessionsRepo
            -> shouldReceive('getByKey')
            -> withArgs([
                'rofl' ,
            ])
            -> andReturn($authSession);

        $result = $authSessionsHandler -> getByKey('rofl');

        $this -> assertInstanceOf(AuthSession::class, $result);
        $this -> assertSame($authSession, $result);
    }

    public function test_getByKeyIfActiveAndExtendActiveTime_ok()
    {
        $usersHandler = Mockery::mock(UsersHandler::class);
        $authSessionsRepo = Mockery::mock(IAuthSessionsRepo::class);
        $systemSettingsInterface = $this -> mock(ISystemSettingsInterface::class);
        $carbon = $this -> mock(Carbon::class);

        $authSessionsHandler = $this -> mock(AuthSessionsHandler::class . '[extendActiveTime, getByKey]', [
            $authSessionsRepo ,
            $systemSettingsInterface ,
            $usersHandler ,
            $carbon ,
        ]);

        $authSession = $this -> mock(AuthSession::class);

        $authSessionsHandler
            -> shouldReceive('getByKey')
            -> withArgs([
                'rofl' ,
            ])
            -> andReturn($authSession);

        $systemSettingsInterface
            -> shouldReceive('getAuthSessionActiveMinutes')
            -> andReturn(149);

        $updated_at = $this -> mock(Carbon::class);
        $authSession -> updated_at = $updated_at;

        $updated_at
            -> shouldReceive('copy')
            -> andReturnSelf();

        $updated_at
            -> shouldReceive('addMinutes')
            -> withArgs([
                149 ,
            ])
            -> andReturn(2);

        $carbon
            -> shouldReceive('now')
            -> andReturn(1);

        $authSessionsHandler
            -> shouldReceive('extendActiveTime')
            -> withArgs([
                $authSession ,
            ])
            -> andReturn($authSession);

        $result = $authSessionsHandler -> getByKeyIfActiveAndExtendActiveTime('rofl');

        $this -> assertSame($authSession, $result);
    }

    public function test_getByKeyIfActiveAndExtendActiveTime_throwsRecordNotFoundExceptionWhenNowIsAfterExpiryTime()
    {
        $usersHandler = Mockery::mock(UsersHandler::class);
        $authSessionsRepo = Mockery::mock(IAuthSessionsRepo::class);
        $systemSettingsInterface = $this -> mock(ISystemSettingsInterface::class);
        $carbon = $this -> mock(Carbon::class);

        $authSessionsHandler = $this -> mock(AuthSessionsHandler::class . '[getByKey]', [
            $authSessionsRepo ,
            $systemSettingsInterface ,
            $usersHandler ,
            $carbon ,
        ]);

        $authSession = $this -> mock(AuthSession::class);

        $authSessionsHandler
            -> shouldReceive('getByKey')
            -> withArgs([
                'rofl' ,
            ])
            -> andReturn($authSession);

        $systemSettingsInterface
            -> shouldReceive('getAuthSessionActiveMinutes')
            -> andReturn(149);

        $updated_at = $this -> mock(Carbon::class);
        $authSession -> updated_at = $updated_at;

        $updated_at
            -> shouldReceive('copy')
            -> andReturnSelf();

        $updated_at
            -> shouldReceive('addMinutes')
            -> withArgs([
                149 ,
            ])
            -> andReturn(1);

        $carbon
            -> shouldReceive('now')
            -> andReturn(2);

        $this -> expectException(RecordNotFoundException::class);

        $authSessionsHandler -> getByKeyIfActiveAndExtendActiveTime('rofl');
    }
}
