<?php

namespace App\Repos\Concretes\Eloquent\Repos;

use App\API\Constants\Inputs\UsersInputConstants;
use App\Models\User;
use App\Repos\Concretes\Eloquent\Models\User as eUser;
use App\Repos\Contracts\IUsersRepo;
use App\Repos\Exceptions\RecordNotFoundException;
use App\Repos\Exceptions\UniqueConstraintFailureException;
use Hash;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class UsersRepo implements IUsersRepo
{
    private $hasher;
    private $model;

    public function __construct(
    Hasher $hasher,
        eUser $eUser
    ) {
        $this->hasher = $hasher;
        $this->model = $eUser;
    }

    public function all(int $page, int $count): array
    {
        $eUsers = $this
            ->model
            ->all()
            ->forPage($page, $count)
        ;

        $users = [];

        foreach ($eUsers as $eUser) {
            $user = new User();

            $user->id = $eUser->id;
            $user->key = $eUser->key;
            $user->secret = $eUser->secret;
            $user->deleted_at = $eUser->deleted_at;
            $user->created_at = $eUser->created_at;
            $user->updated_at = $eUser->updated_at;

            $users[] = $user;
        }

        return $users;
    }

    public function create(string $userKey, string $userSecret): User
    {
        try {
            $eUser = $this
                ->model
                ->newInstance();

            $eUser->key = $userKey;
            $eUser->secret = Hash::make($userSecret);

            $eUser->save();

            $user = new User();

            $user->id = $eUser->id;
            $user->key = $eUser->key;

            return $user;
        } catch (QueryException $ex) {
            throw new UniqueConstraintFailureException(UsersInputConstants::UserKey, $userKey);
        }
    }

    public function get(int $id): User
    {
        try {
            $eUser = $this
                ->model
                ->findOrFail($id);

            $user = new User();

            $user->id = $eUser->id;
            $user->key = $eUser->key;
            $user->secret = $eUser->secret;
            $user->deleted_at = $eUser->deleted_at;
            $user->created_at = $eUser->created_at;
            $user->updated_at = $eUser->updated_at;

            return $user;
        } catch (ModelNotFoundException $ex) {
            throw new RecordNotFoundException();
        }
    }

    public function getByKey(string $userKey): User
    {
        try {
            $eUser = $this
                ->model
                ->where('key', '=', $userKey)
                ->firstOrFail();

            $user = new User();

            $user->id = $eUser->id;
            $user->key = $eUser->key;
            $user->secret = $eUser->secret;
            $user->deleted_at = $eUser->deleted_at;
            $user->created_at = $eUser->created_at;
            $user->updated_at = $eUser->updated_at;

            return $user;
        } catch (ModelNotFoundException $ex) {
            throw new RecordNotFoundException();
        }
    }

    public function update(int $id, array $data): User
    {
        try {
            $eUser = $this
                ->model
                ->findOrFail($id);

            if (array_key_exists(UsersInputConstants::UserSecret, $data)) {
                $eUser->secret = $this
                    ->hasher
                    ->make($data[UsersInputConstants::UserSecret]);
            }

            $eUser->save();

            return $this->get($id);
        } catch (ModelNotFoundException $ex) {
            throw new RecordNotFoundException();
        }
    }
}
