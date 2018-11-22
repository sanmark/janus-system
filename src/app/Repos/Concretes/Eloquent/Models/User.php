<?php

namespace App\Repos\Concretes\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public function metaFirstName() {
        return $this->meta('first_name');
    }
    
    public function metaLastName() {
        return $this->meta('last_name');
    }
    
    public function metaName() {
        return $this->meta('name');
    }
    
    public function meta($metaKey) {
        return $this
            ->hasOne(Meta::class)
            ->leftJoin('meta_keys', 'meta_keys.id', '=', 'metas.meta_key_id')
            ->where('meta_keys.key', '=', $metaKey)
        ;
    }
}
