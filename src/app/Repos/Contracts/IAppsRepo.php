<?php

namespace App\Repos\Contracts;

use App\Models\App;

interface IAppsRepo
{
    public function getByKey(string $key): App;
}
