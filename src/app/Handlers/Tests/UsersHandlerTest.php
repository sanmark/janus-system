<?php

namespace App\Handlers\Tests;

use App\Handlers\UsersHandler;
use App\Helpers\ArrayHelper;
use App\Models\User;
use App\Repos\Contracts\IUsersRepo;
use App\Repos\Exceptions\RecordNotFoundException;
use Illuminate\Contracts\Hashing\Hasher;
use Mockery;
use Tests\TestCase;

/**
 * @codeCoverageIgnore
 */
class UsersHandlerTest extends TestCase
{
    public function test_all_ok()
    {
        $mockArrayHelper = Mockery::mock(ArrayHelper::class);
        $mockHash = Mockery::mock(Hasher::class);
        $mockIUsersRepo = Mockery::mock(IUsersRepo::class);

        $mockIUsersRepo
            ->shouldReceive('all')
            ->withArgs([
                false,
                149,
                150,
                '152',
                '153',
            ])
            ->andReturn([151])
        ;

        $usersHandler = new UsersHandler($mockArrayHelper, $mockHash, $mockIUsersRepo);

        $response = $usersHandler->all(false, 149, 150, 152, 153);

        $this->assertSame([151], $response);
    }

    public function test_create_Ok()
    {
        $mockArrayHelper = Mockery::mock(ArrayHelper::class);
        $mockHash = Mockery::mock(Hasher::class);
        $mockIUsersRepo = Mockery::mock(IUsersRepo::class);
        $userKey = $this->faker()->userName;
        $userSecret = $this->faker()->password;
        $mockUserModel = Mockery::mock(User::class);

        $mockIUsersRepo
            ->shouldReceive('create')
            ->withArgs([
                $userKey,
                $userSecret,
            ])
            ->andReturn($mockUserModel);

        $usersHandler = new UsersHandler($mockArrayHelper, $mockHash, $mockIUsersRepo);

        $response = $usersHandler->create($userKey, $userSecret);

        $this->assertSame($mockUserModel, $response);
    }

    public function test_get_ok()
    {
        $arrayHelper = $this->mock(ArrayHelper::class);
        $hash = $this->mock(Hasher::class);
        $iUsersRepo = $this->mock(IUsersRepo::class);
        $user = $this->mock(User::class);

        $usersHandler = new UsersHandler($arrayHelper, $hash, $iUsersRepo);

        $iUsersRepo->shouldReceive('get')
            ->withArgs([149])
            ->andReturn($user);

        $response = $usersHandler->get(149);

        $this->assertSame($user, $response);
    }

    public function test_getByKey_ok()
    {
        $arrayHelper = $this->mock(ArrayHelper::class);
        $hash = $this->mock(Hasher::class);
        $iUsersRepo = $this->mock(IUsersRepo::class);
        $user = $this->mock(User::class);

        $usersHandler = new UsersHandler($arrayHelper, $hash, $iUsersRepo);

        $iUsersRepo->shouldReceive('getByKey')
            ->withArgs(['key'])
            ->andReturn($user);

        $response = $usersHandler->getByKey('key');

        $this->assertSame($user, $response);
    }

    public function test_getUserIfCredentialsValid_Ok()
    {
        $mockArrayHelper = Mockery::mock(ArrayHelper::class);
        $mockIUsersRepo = Mockery::mock(IUsersRepo::class);
        $mockHash = Mockery::mock(Hasher::class);
        $mockUser = Mockery::mock(User::class);

        $mockIUsersRepo
            ->shouldReceive('getByKey')
            ->withArgs([
                'the_key',
            ])
            ->andReturn($mockUser);

        $mockHash
            ->shouldReceive('check')
            ->withArgs([
                'the_secret',
                'asdf',
            ])
            ->andReturn(true);

        $mockUser->secret = 'asdf';

        $usersHandler = new UsersHandler($mockArrayHelper, $mockHash, $mockIUsersRepo);

        $response = $usersHandler->getUserIfCredentialsValid('the_key', 'the_secret');

        $this->assertSame($mockUser, $response);
    }

    public function test_getUserIfCredentialsValid_HandlesInvalidCredentials()
    {
        $this->expectException(RecordNotFoundException::class);

        $mockArrayHelper = $this->mock(ArrayHelper::class);
        $mockHash = $this->mock(Hasher::class);
        $mockIUsersRepo = $this->mock(IUsersRepo::class);

        $usersHandler = new UsersHandler($mockArrayHelper, $mockHash, $mockIUsersRepo);

        $user = $this->mock(User::class);

        $user->secret = 'the_secret_saved_in_db';

        $mockIUsersRepo
            ->shouldReceive('getByKey')
            ->withArgs([
                'the_key',
            ])
            ->andReturn($user);

        $mockHash
            ->shouldReceive('check')
            ->withArgs([
                'the_secret',
                'the_secret_saved_in_db',
            ])
            ->andReturn(false);

        $usersHandler->getUserIfCredentialsValid('the_key', 'the_secret');
    }

    public function test_update_ok()
    {
        $arrayHelper = $this->mock(ArrayHelper::class);
        $hash = $this->mock(Hasher::class);
        $iUsersRepo = $this->mock(IUsersRepo::class);
        $user = $this->mock(User::class);

        $usersHandler = new UsersHandler($arrayHelper, $hash, $iUsersRepo);

        $arrayHelper->shouldReceive('onlyNonEmptyMembers')
            ->withArgs([
                [
                    'lol' => 'rofl',
                ],
            ])
            ->andReturn([
                'foo' => 'bar',
            ]);

        $iUsersRepo->shouldReceive('update')
            ->withArgs([
                149,
                [
                    'foo' => 'bar',
                ],
            ])
            ->andReturn($user);

        $response = $usersHandler->update(149, [
            'lol' => 'rofl',
        ]);

        $this->assertSame($user, $response);
    }
}
