<?php

namespace App\Handlers;

use App\Helpers\ArrayHelper;
use App\Models\User;
use App\Repos\Contracts\IUsersRepo;
use App\Repos\Exceptions\RecordNotFoundException;
use Illuminate\Contracts\Hashing\Hasher;

class UsersHandler
{
    private $arrayHelper;
    private $hash;
    private $usersRepo;

    public function __construct(
    ArrayHelper $arrayHelper,
        Hasher $hash,
        IUsersRepo $iUsersRepo
    ) {
        $this -> arrayHelper = $arrayHelper;
        $this -> hash = $hash;
        $this -> usersRepo = $iUsersRepo;
    }

    public function create(string $userKey, string $userSecret): User
    {
        return $this
                -> usersRepo
                -> create($userKey, $userSecret);
    }

    public function get(int $id): User
    {
        $user = $this
            -> usersRepo
            -> get($id);

        return $user;
    }

    public function getByKey(string $key): User
    {
        $user = $this
            -> usersRepo
            -> getByKey($key);

        return $user;
    }

    public function getUserIfCredentialsValid(string $userKey, string $userSecret): User
    {
        $user = $this
            -> usersRepo
            -> getByKey($userKey);

        if ($this -> hash -> check($userSecret, $user -> secret)) {
            return $user;
        }

        throw new RecordNotFoundException();
    }

    public function update(int $id, array $data): User
    {
        $cleanedData = $this
            -> arrayHelper
            -> onlyNonEmptyMembers($data);

        $user = $this
            -> usersRepo
            -> update($id, $cleanedData);

        return $user;
    }
}
