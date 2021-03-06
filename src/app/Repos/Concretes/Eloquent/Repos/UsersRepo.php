<?php

namespace App\Repos\Concretes\Eloquent\Repos;

use App\API\Constants\Inputs\UsersInputConstants;
use App\API\Validators\Exceptions\InvalidInputException;
use App\Models\User;
use App\Repos\Concretes\Eloquent\Models\User as eUser;
use App\Repos\Contracts\IUsersRepo;
use App\Repos\Exceptions\RecordNotFoundException;
use App\Repos\Exceptions\UniqueConstraintFailureException;
use Hash;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\QueryException;

class UsersRepo implements IUsersRepo
{
    private $hasher;
    private $metaKeysRepo;
    private $model;

    public function __construct(
        Hasher $hasher,
        MetaKeysRepo $metaKeysRepo,
        eUser $eUser
    ) {
        $this->hasher = $hasher;
        $this->metaKeysRepo = $metaKeysRepo;
        $this->model = $eUser;
    }

    public function all(
        bool $noPagination = false,
        int $page = 1,
        int $count = 10,
        string $orderBy = 'id',
        string $orderSort = 'asc',
        string $metaOrderBy = null,
        string $metaOrderSort = 'asc',
        array $withMetas = [],
        array $filters = []
    ): array {
        $query = $this
            ->model
            ->query()
        ;

        foreach ($filters as $filter) {
            if (
                $filter[0] === 'id' &&
                is_array($filter[1])
            ) {
                //Eg: [["id", [149,150,151]]]
                $query->whereIn('id', $filter[1]);
            }
        }

        foreach ($withMetas as $withMeta) {
            $query->with($withMeta);
        }

        if (!is_null($metaOrderBy)) {
            try {
                $metaKeyId = $this
                        ->metaKeysRepo
                        ->getByKey($metaOrderBy)
                    ->id
                ;

                $query->join('metas', function (JoinClause $join) use ($metaKeyId) {
                    $join
                        ->on('metas.user_id', '=', 'users.id')
                        ->where('metas.meta_key_id', '=', $metaKeyId)
                    ;
                });

                $query->orderBy('metas.value', $metaOrderSort);
            } catch (RecordNotFoundException $ex) {
                throw new InvalidInputException([
                    'meta_order_by' => [
                        'value_not_found',
                    ],
                ]);
            }
        } else {
            $query->orderBy($orderBy, $orderSort);
        }

        if (!$noPagination) {
            $query->forPage($page, $count);
        }

        $eUsers = $query->get(['users.*']);

        $users = [];

        foreach ($eUsers as $eUser) {
            $user = new User();

            $user->id = $eUser->id;
            $user->key = $eUser->key;
            $user->secret = $eUser->secret;
            $user->deleted_at = $eUser->deleted_at;
            $user->created_at = $eUser->created_at;
            $user->updated_at = $eUser->updated_at;

            foreach ($withMetas as $withMeta) {
                if (!is_null($eUser->{$withMeta})) {
                    $user->{$withMeta} = $eUser->{$withMeta}->value;
                }
            }

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
