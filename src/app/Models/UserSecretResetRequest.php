<?php

namespace App\Models;

class UserSecretResetRequest extends Base
{
    public $id;
    public $user_id;
    public $token;
    public $created_at;
    public $updated_at;
}
