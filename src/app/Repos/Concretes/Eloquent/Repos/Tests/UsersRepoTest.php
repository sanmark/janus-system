<?php

namespace App\Repos\Concretes\Eloquent\Repos\Tests;

use App\Models\User;
use App\Repos\Concretes\Eloquent\Models\User as eUser;
use App\Repos\Concretes\Eloquent\Repos\MetaKeysRepo;
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
        $hasher = $this->mock(Hasher::class);
        $metaKeysRepo = $this->mock(MetaKeysRepo::class);
        $eUser = $this->mock(eUser::class);

        $usersRepo = new UsersRepo($hasher, $metaKeysRepo, $eUser);

        $eUser
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

        $eUser
            ->shouldReceive('query')
            ->withNoArgs()
            ->andReturnSelf()
        ;

        $eUser
            ->shouldReceive('forPage')
            ->withArgs([
                149,
                150,
            ])
            ->andReturnSelf()
        ;

        $eUser
            ->shouldReceive('orderBy')
            ->withArgs([
                '157',
                '158',
            ])
            ->andReturnSelf()
        ;

        $eUser
            ->shouldReceive('get')
            ->withArgs([['users.*']])
            ->andReturn([$user])
        ;

        $expectedResponse = new User();
        $expectedResponse->id = 151;
        $expectedResponse->key = 152;
        $expectedResponse->secret = 153;
        $expectedResponse->deleted_at = 154;
        $expectedResponse->created_at = 155;
        $expectedResponse->updated_at = 156;

        $response = $usersRepo->all(false, 149, 150, 157, 158);

        $this->assertEquals([$expectedResponse], $response);
    }

    public function test_create_ok()
    {
        $hasher = $this->mock(Hasher::class);
        $metaKeysRepo = $this->mock(MetaKeysRepo::class);
        $eUser = $this->mock(eUser::class);

        $usersRepo = new UsersRepo($hasher, $metaKeysRepo, $eUser);

        $eUser
            ->shouldReceive('newInstance')
            ->andReturn($eUser);

        $eUser
            ->shouldReceive('setAttribute');

        $eUser
            ->shouldReceive('save');

        $eUser
            ->shouldReceive('getAttribute');

        $userKey = $this->faker()->userName;
        $userSecret = $this->faker()->password;

        $user = $usersRepo->create($userKey, $userSecret);

        $this->assertInstanceOf(User::class, $user);
    }

    public function test_create_throwsUniqueConstraintFailureException()
    {
        $this->expectException(UniqueConstraintFailureException::class);

        $hasher = $this->mock(Hasher::class);
        $metaKeysRepo = $this->mock(MetaKeysRepo::class);
        $eUser = $this->mock(eUser::class);

        $usersRepo = new UsersRepo($hasher, $metaKeysRepo, $eUser);

        $mockPreviousException = $this->mock(Exception::class);

        $eUser
            ->shouldReceive('newInstance')
            ->andReturn($eUser);

        $eUser
            ->shouldReceive('setAttribute');

        $eUser
            ->shouldReceive('save')
            ->andThrow(new QueryException('sql', [], $mockPreviousException));

        $userKey = $this->faker()->userName;
        $userSecret = $this->faker()->password;

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
        $metaKeysRepo = $this->mock(MetaKeysRepo::class);
        $eUser = $this->mock(eUser::class);

        $usersRepo = new UsersRepo($hasher, $metaKeysRepo, $eUser);

        $eUser->shouldReceive('getAttribute')
            ->withArgs([
                'id',
            ])
            ->andReturn(149);

        $eUser->shouldReceive('getAttribute')
            ->withArgs([
                'key',
            ])
            ->andReturn('the-key');

        $eUser->shouldReceive('getAttribute')
            ->withArgs([
                'secret',
            ])
            ->andReturn('the-secret');

        $eUser->shouldReceive('getAttribute')
            ->withArgs([
                'deleted_at',
            ])
            ->andReturn('the-deleted_at');

        $eUser->shouldReceive('getAttribute')
            ->withArgs([
                'created_at',
            ])
            ->andReturn('the-created_at');

        $eUser->shouldReceive('getAttribute')
            ->withArgs([
                'updated_at',
            ])
            ->andReturn('the-updated_at');

        $eUser->shouldReceive('findOrFail')
            ->withArgs([
                149,
            ])
            ->andReturn($eUser);

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
        $metaKeysRepo = $this->mock(MetaKeysRepo::class);
        $eUser = $this->mock(eUser::class);

        $usersRepo = new UsersRepo($hasher, $metaKeysRepo, $eUser);

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
        $hasher = $this->mock(Hasher::class);
        $metaKeysRepo = $this->mock(MetaKeysRepo::class);
        $eUser = $this->mock(eUser::class);

        $usersRepo = new UsersRepo($hasher, $metaKeysRepo, $eUser);

        $eUser
            ->shouldReceive('where')
            ->withArgs([
                'key',
                '=',
                'rofl',
            ])
            ->andReturn($eUser);

        $eUser
            ->shouldReceive('firstOrFail')
            ->andReturn($eUser);

        $attributeValues = [
            'id' => 'the_id',
            'key' => 'the_key',
            'secret' => 'the_secret',
            'deleted_at' => 'the_deleted_at',
            'created_at' => 'the_created_at',
            'updated_at' => 'the_updated_at',
        ];

        foreach ($attributeValues as $attribute => $value) {
            $eUser
                ->shouldReceive('getAttribute')
                ->withArgs([$attribute])
                ->andReturn($value);
        }

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

        $hasher = $this->mock(Hasher::class);
        $metaKeysRepo = $this->mock(MetaKeysRepo::class);
        $eUser = $this->mock(eUser::class);

        $usersRepo = new UsersRepo($hasher, $metaKeysRepo, $eUser);

        $eUser
            ->shouldReceive('where')
            ->withArgs([
                'key',
                '=',
                'rofl',
            ])
            ->andThrow(ModelNotFoundException::class);

        $user = $usersRepo
            ->getByKey('rofl');
    }

    public function test_update_ok()
    {
        $hasher = $this->mock(Hasher::class);
        $metaKeysRepo = $this->mock(MetaKeysRepo::class);
        $eUser = $this->mock(eUser::class);

        $usersRepo = $this->mock(UsersRepo::class . '[get]', [
            $hasher,
            $metaKeysRepo,
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
        $metaKeysRepo = $this->mock(MetaKeysRepo::class);
        $eUser = $this->mock(eUser::class);

        $usersRepo = $this->mock(UsersRepo::class . '[get]', [
            $hasher,
            $metaKeysRepo,
            $eUser,
        ]);

        $eUser->shouldReceive('findOrFail')
            ->withArgs([
                149,
            ])
            ->andThrow(ModelNotFoundException::class);

        $this->expectException(RecordNotFoundException::class);

        $usersRepo->update(149, []);
    }
}
