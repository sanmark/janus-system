<?php

namespace App\Repos\Concretes\Eloquent\Repos;

use App\Models\AuthSession;
use App\Repos\Concretes\Eloquent\Models\AuthSession as eAuthSession;
use App\Repos\Contracts\IAuthSessionsRepo;
use App\Repos\Exceptions\RecordNotFoundException;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use function str_random;

class AuthSessionsRepo implements IAuthSessionsRepo
{
    private $model;
    private $usersRepo;
    private $hash;

    public function __construct(
    Hasher $hash,
        eAuthSession $eAuthSession,
        UsersRepo $usersRepo
    ) {
        $this -> hash = $hash;
        $this -> model = $eAuthSession;
        $this -> usersRepo = $usersRepo;
    }

    public function create(int $userId): AuthSession
    {
        $eAuthSession = $this
            -> model
            -> newInstance();

        $eAuthSession -> key = $this
            -> hash
            -> make(str_random());
        $eAuthSession -> user_id = $userId;

        $eAuthSession -> save();

        $authSession = new AuthSession();
        $authSession -> id = $eAuthSession -> id;
        $authSession -> key = $eAuthSession -> key;
        $authSession -> user_id = $userId;

        return $authSession;
    }

    public function getByKey(string $key): AuthSession
    {
        try {
            $eAuthSession = $this
                -> model
                -> where('key', '=', $key)
                -> firstOrFail();

            $authSession = new AuthSession();
            $authSession -> id = $eAuthSession -> id;
            $authSession -> key = $eAuthSession -> key;
            $authSession -> user_id = $eAuthSession -> user_id;
            $authSession -> is_permanent = $eAuthSession -> is_permanent;
            $authSession -> created_at = $eAuthSession -> created_at;
            $authSession -> updated_at = $eAuthSession -> updated_at;

            return $authSession;
        } catch (ModelNotFoundException $ex) {
            throw new RecordNotFoundException();
        }
    }

    public function update(AuthSession $authSession): AuthSession
    {
        $eAuthSession = $this
            -> model
            -> findOrFail($authSession -> id);

        $eAuthSession -> updated_at = $authSession -> updated_at;

        $eAuthSession -> save();

        return $this -> getByKey($eAuthSession -> key);
    }
}
