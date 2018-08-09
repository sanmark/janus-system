<?php

namespace App\Repos\Concretes\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class MetaKey extends Model
{
    public function metas()
    {
        return $this
                -> hasMany(Meta::class);
    }
}
