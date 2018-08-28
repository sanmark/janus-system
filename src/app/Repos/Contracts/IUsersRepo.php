<?php

namespace App\Repos\Contracts;

use App\Models\User;

interface IUsersRepo
{
    public function all(
        bool $noPagination = false,
        int $page = 1,
        int $count = 10,
        string $orderBy = 'id',
        string $orderSort = 'asc',
        string $metaOrderBy = null,
        string $metaOrderSort = 'asc'
    ): array;

    public function create(string $userKey, string $userSecret): User;

    public function get(int $id): User;

    public function getByKey(string $userKey): User;

    public function update(int $id, array $data): User;
}
