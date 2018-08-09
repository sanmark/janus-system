<?php

namespace App\Handlers;

use App\API\Constants\Inputs\UsersInputConstants;
use App\Models\User;
use App\Models\UserSecretResetRequest;
use App\Repos\Contracts\IUserSecretResetRequestsRepo;
use App\Repos\Exceptions\RecordNotFoundException;

class UserSecretResetRequestsHandler
{
    private $userSecretResetRequestsRepo;
    private $usersHandler;

    public function __construct(
    IUserSecretResetRequestsRepo $userSecretResetRequestsRepo,
        UsersHandler $usersHandler
    ) {
        $this -> userSecretResetRequestsRepo = $userSecretResetRequestsRepo;
        $this -> usersHandler = $usersHandler;
    }

    public function create(int $userId): UserSecretResetRequest
    {
        $user = $this
            -> usersHandler
            -> get($userId);

        return $this
                -> userSecretResetRequestsRepo
                -> create($user -> id);
    }

    public function execute(int $userId, string $userSecretResetRequestToken, string $newSecret): User
    {
        $user = $this
            -> usersHandler
            -> get($userId);

        $userSecretResetRequest = $this
            -> userSecretResetRequestsRepo
            -> getByToken($userSecretResetRequestToken);

        if ($userSecretResetRequest -> user_id != $user -> id) {
            throw new RecordNotFoundException();
        }

        $user = $this
            -> usersHandler
            -> update($user -> id, [
                UsersInputConstants::UserSecret => $newSecret ,
            ]);

        $this -> deleteOfUser($user -> id);

        return $user;
    }

    private function deleteOfUser(int $userId)
    {
        $this
            -> userSecretResetRequestsRepo
            -> deleteOfUser($userId);
    }
}
