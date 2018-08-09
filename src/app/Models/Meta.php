<?php

namespace App\Models;

use App\Repos\Contracts\IMetaKeysRepo;
use function app;

class Meta extends Base
{
    public $id;
    public $meta_key_id;
    public $user_id;
    public $value;
    public $created_at;
    public $updated_at;

    private $metaKeysRepo;

    public function __construct()
    {
        $this -> metaKeysRepo = app(IMetaKeysRepo::class);
    }

    public function getMetaKey()
    {
        $metaKey = $this -> metaKeysRepo -> find($this -> meta_key_id);
        if (! $metaKey) {
            return null;
        }

        return $metaKey -> key;
    }
}
