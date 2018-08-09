<?php

namespace App\Repos\Concretes\Eloquent\Repos;

use App\Models\UserSecretResetRequest;
use App\Repos\Concretes\Eloquent\Models\UserSecretResetRequest as eUserSecretResetRequest;
use App\Repos\Contracts\IUserSecretResetRequestsRepo;
use App\Repos\Exceptions\RecordNotFoundException;
use Carbon\Carbon;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserSecretResetRequestsRepo implements IUserSecretResetRequestsRepo
{
    private $carbon;
    private $model;
    private $hasher;

    public function __construct(
    Carbon $carbon,
        eUserSecretResetRequest $eUserSecretResetRequest,
        Hasher $hasher
    ) {
        $this -> carbon = $carbon;
        $this -> model = $eUserSecretResetRequest;
        $this -> hasher = $hasher;
    }

    public function create(int $userId): UserSecretResetRequest
    {
        $eUserSecretResetRequest = $this
            -> model
            -> newInstance();

        $eUserSecretResetRequest -> user_id = $userId;
        $eUserSecretResetRequest -> token = $this -> generateToken();

        $eUserSecretResetRequest -> save();

        $userSecretResetRequest = new UserSecretResetRequest();

        $userSecretResetRequest -> id = $eUserSecretResetRequest -> id;
        $userSecretResetRequest -> user_id = $eUserSecretResetRequest -> user_id;
        $userSecretResetRequest -> token = $eUserSecretResetRequest -> token;
        $userSecretResetRequest -> created_at = $eUserSecretResetRequest -> created_at;
        $userSecretResetRequest -> updated_at = $eUserSecretResetRequest -> updated_at;

        return $userSecretResetRequest;
    }

    public function deleteOfUser(int $userId)
    {
        $eUserSecretResetRequests = $this
            -> model
            -> where('user_id', '=', $userId)
            -> get();

        foreach ($eUserSecretResetRequests as $eUserSecretResetRequest) {
            $eUserSecretResetRequest -> delete();
        }
    }

    public function getByToken(string $token): UserSecretResetRequest
    {
        try {
            $eUserSecretResetRequest = $this
                -> model
                -> where('token', '=', $token)
                -> firstOrFail();

            $userSecretResetRequest = new UserSecretResetRequest();

            $userSecretResetRequest -> id = $eUserSecretResetRequest -> id;
            $userSecretResetRequest -> user_id = $eUserSecretResetRequest -> user_id;
            $userSecretResetRequest -> token = $eUserSecretResetRequest -> token;
            $userSecretResetRequest -> created_at = $eUserSecretResetRequest -> created_at;
            $userSecretResetRequest -> updated_at = $eUserSecretResetRequest -> updated_at;

            return $userSecretResetRequest;
        } catch (ModelNotFoundException $ex) {
            throw new RecordNotFoundException();
        }
    }

    private function generateToken(): string
    {
        $now = $this
            -> carbon
            -> now();

        $token = $this
            -> hasher
            -> make($now);

        return $token;
    }
}
