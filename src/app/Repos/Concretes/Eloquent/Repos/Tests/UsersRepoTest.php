<?php

namespace App\Repos\Concretes\Eloquent\Repos\Tests;

use App\Models\User;
use App\Repos\Concretes\Eloquent\Models\User as eUser;
use App\Repos\Concretes\Eloquent\Repos\UsersRepo;
use App\Repos\Exceptions\RecordNotFoundException;
use App\Repos\Exceptions\UniqueConstraintFailureException;
use Exception;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use stdClass;
use Tests\TestCase;

/**
 * @codeCoverageIgnore
 */
class UsersRepoTest extends TestCase
{
    public function test_all_ok()
    {
        $mockEUser = $this->mock(eUser::class);
        $mockHasher = $this->mock(Hasher::class);

        $mockEUser
            ->shouldReceive('all')
            ->withNoArgs()
            ->andReturnSelf()
        ;

        $user = $this->mock(stdClass::class);
        $user->id = 151;
        $user->key = 152;
        $user->secret = 153;
        $user->deleted_at = 154;
        $user->created_at = 155;
        $user->updated_at = 156;

        $mockEUser
            ->shouldReceive('query')
            ->withNoArgs()
            ->andReturnSelf()
        ;

        $mockEUser
            ->shouldReceive('forPage')
            ->withArgs([
                149,
                150,
            ])
            ->andReturnSelf()
        ;

        $mockEUser
            ->shouldReceive('orderBy')
            ->withArgs([
                '157',
                '158',
            ])
            ->andReturnSelf()
        ;

        $mockEUser
            ->shouldReceive('get')
            ->withNoArgs()
            ->andReturn([$user])
        ;

        $expectedResponse = new User();
        $expectedResponse->id = 151;
        $expectedResponse->key = 152;
        $expectedResponse->secret = 153;
        $expectedResponse->deleted_at = 154;
        $expectedResponse->created_at = 155;
        $expectedResponse->updated_at = 156;

        $usersRepo = new UsersRepo($mockHasher, $mockEUser);

        $response = $usersRepo->all(false, 149, 150, 157, 158);

        $this->assertEquals([$expectedResponse], $response);
    }

    public function test_create_ok()
    {
        $mockEUser = $this->mock(eUser::class);
        $mockHasher = $this->mock(Hasher::class);

        $mockEUser
            ->shouldReceive('newInstance')
            ->andReturn($mockEUser);

        $mockEUser
            ->shouldReceive('setAttribute');

        $mockEUser
            ->shouldReceive('save');

        $mockEUser
            ->shouldReceive('getAttribute');

        $usersRepo = new UsersRepo($mockHasher, $mockEUser);

        $userKey = $this->faker()->userName;
        $userSecret = $this->faker()->password;

        $user = $usersRepo->create($userKey, $userSecret);

        $this->assertInstanceOf(User::class, $user);
    }

    public function test_create_throwsUniqueConstraintFailureException()
    {
        $this->expectException(UniqueConstraintFailureException::class);

        $mockEUser = $this->mock(eUser::class);
        $mockHasher = $this->mock(Hasher::class);
        $mockPreviousException = $this->mock(Exception::class);

        $mockEUser
            ->shouldReceive('newInstance')
            ->andReturn($mockEUser);

        $mockEUser
            ->shouldReceive('setAttribute');

        $mockEUser
            ->shouldReceive('save')
            ->andThrow(new QueryException('sql', [], $mockPreviousException));

        $userKey = $this->faker()->userName;
        $userSecret = $this->faker()->password;

        $usersRepo = new UsersRepo($mockHasher, $mockEUser);

        try {
            $usersRepo->create($userKey, $userSecret);
        } catch (UniqueConstraintFailureException $ex) {
            $this->assertSame('user_key', $ex->getConstraint());

            throw $ex;
        }
    }

    public function test_get_ok()
    {
        $hasher = $this->mock(Hasher::class);
        $eUser = $this->mock(eUser::class);

        $usersRepo = new UsersRepo($hasher, $eUser);

        $theEUser = $this->mock(eUser::class);

        $theEUser->shouldReceive('getAttribute')
            ->withArgs([
                'id',
            ])
            ->andReturn(149);

        $theEUser->shouldReceive('getAttribute')
            ->withArgs([
                'key',
            ])
            ->andReturn('the-key');

        $theEUser->shouldReceive('getAttribute')
            ->withArgs([
                'secret',
            ])
            ->andReturn('the-secret');

        $theEUser->shouldReceive('getAttribute')
            ->withArgs([
                'deleted_at',
            ])
            ->andReturn('the-deleted_at');

        $theEUser->shouldReceive('getAttribute')
            ->withArgs([
                'created_at',
            ])
            ->andReturn('the-created_at');

        $theEUser->shouldReceive('getAttribute')
            ->withArgs([
                'updated_at',
            ])
            ->andReturn('the-updated_at');

        $eUser->shouldReceive('findOrFail')
            ->withArgs([
                149,
            ])
            ->andReturn($theEUser);

        $response = $usersRepo->get(149);

        $expectedUser = new User();
        $expectedUser->id = 149;
        $expectedUser->key = 'the-key';
        $expectedUser->secret = 'the-secret';
        $expectedUser->deleted_at = 'the-deleted_at';
        $expectedUser->created_at = 'the-created_at';
        $expectedUser->updated_at = 'the-updated_at';

        $this->assertEquals($expectedUser, $response);
    }

    public function test_get_throwsRecordNotFoundException()
    {
        $hasher = $this->mock(Hasher::class);
        $eUser = $this->mock(eUser::class);

        $usersRepo = new UsersRepo($hasher, $eUser);

        $eUser->shouldReceive('findOrFail')
            ->withArgs([
                149,
            ])
            ->andThrow(ModelNotFoundException::class);

        $this->expectException(RecordNotFoundException::class);

        $usersRepo->get(149);
    }

    public function test_getByKey_ok()
    {
        $mockEUser = $this->mock(eUser::class);
        $mockHasher = $this->mock(Hasher::class);

        $mockEUser
            ->shouldReceive('where')
            ->withArgs([
                'key',
                '=',
                'rofl',
            ])
            ->andReturn($mockEUser);

        $mockEUser
            ->shouldReceive('firstOrFail')
            ->andReturn($mockEUser);

        $attributeValues = [
            'id' => 'the_id',
            'key' => 'the_key',
            'secret' => 'the_secret',
            'deleted_at' => 'the_deleted_at',
            'created_at' => 'the_created_at',
            'updated_at' => 'the_updated_at',
        ];

        foreach ($attributeValues as $attribute => $value) {
            $mockEUser
                ->shouldReceive('getAttribute')
                ->withArgs([$attribute])
                ->andReturn($value);
        }

        $usersRepo = new UsersRepo($mockHasher, $mockEUser);

        $user = $usersRepo
            ->getByKey('rofl');

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame($user->id, 'the_id');
        $this->assertSame($user->key, 'the_key');
        $this->assertSame($user->secret, 'the_secret');
        $this->assertSame($user->deleted_at, 'the_deleted_at');
        $this->assertSame($user->created_at, 'the_created_at');
        $this->assertSame($user->updated_at, 'the_updated_at');
    }

    public function test_getByKey_throwsRecordNotFoundException()
    {
        $this->expectException(RecordNotFoundException::class);

        $mockEUser = $this->mock(eUser::class);
        $mockHasher = $this->mock(Hasher::class);

        $mockEUser
            ->shouldReceive('where')
            ->withArgs([
                'key',
                '=',
                'rofl',
            ])
            ->andThrow(ModelNotFoundException::class);

        $usersRepo = new UsersRepo($mockHasher, $mockEUser);

        $user = $usersRepo
            ->getByKey('rofl');
    }

    public function test_update_ok()
    {
        $hasher = $this->mock(Hasher::class);
        $eUser = $this->mock(eUser::class);

        $usersRepo = $this->mock(UsersRepo::class . '[get]', [
            $hasher,
            $eUser,
        ]);

        $theUser = $this->mock(eUser::class);

        $eUser->shouldReceive('findOrFail')
            ->withArgs([
                149,
            ])
            ->andReturn($theUser);

        $hasher->shouldReceive('make')
            ->withArgs([
                'the-secret',
            ])
            ->andReturn(150);

        $theUser->shouldReceive('setAttribute')
            ->withArgs([
                'secret',
                150,
            ])
            ->andReturnSelf();

        $theUser->shouldReceive('save')
            ->withArgs([]);

        $user = $this->mock(User::class);

        $usersRepo->shouldReceive('get')
            ->withArgs([
                149,
            ])
            ->andReturn($user);

        $response = $usersRepo->update(149, ['user_secret' => 'the-secret']);

        $this->assertSame($user, $response);
    }

    public function test_update_throwsRecordNotFoundException()
    {
        $hasher = $this->mock(Hasher::class);
        $eUser = $this->mock(eUser::class);

        $usersRepo = new UsersRepo($hasher, $eUser);

        $eUser->shouldReceive('findOrFail')
            ->withArgs([
                149,
            ])
            ->andThrow(ModelNotFoundException::class);

        $this->expectException(RecordNotFoundException::class);

        $usersRepo->update(149, []);
    }
}
