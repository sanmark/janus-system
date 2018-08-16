<?php

namespace App\Repos\Contracts;

use App\Models\User;

interface IUsersRepo
{
    public function all(int $page, int $count): array;

    public function create(string $userKey, string $userSecret): User;

    public function get(int $id): User;

    public function getByKey(string $userKey): User;

    public function update(int $id, array $data): User;
}
