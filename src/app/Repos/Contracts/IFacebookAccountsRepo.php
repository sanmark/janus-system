<?php

namespace App\Repos\Contracts;

use App\Models\FacebookAccount;

interface IFacebookAccountsRepo
{
    public function create(int $userId, string $key): FacebookAccount;

    public function getByKey(string $key): FacebookAccount;
}
