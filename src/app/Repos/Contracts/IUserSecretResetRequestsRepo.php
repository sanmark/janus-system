<?php

namespace App\Repos\Contracts;

use App\Models\UserSecretResetRequest;

interface IUserSecretResetRequestsRepo
{
    public function create(int $userId): UserSecretResetRequest;

    public function deleteOfUser(int $userId);

    public function getByToken(string $token): UserSecretResetRequest;
}
