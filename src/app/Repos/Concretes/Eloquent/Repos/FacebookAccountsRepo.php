<?php

namespace App\Repos\Concretes\Eloquent\Repos;

use App\Models\FacebookAccount;
use App\Repos\Concretes\Eloquent\Models\FacebookAccount as eFacebookAccount;
use App\Repos\Contracts\IFacebookAccountsRepo;
use App\Repos\Exceptions\RecordNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FacebookAccountsRepo implements IFacebookAccountsRepo
{
    private $model;

    public function __construct(
    eFacebookAccount $eFacebookAccount
    ) {
        $this -> model = $eFacebookAccount;
    }

    public function create(int $userId, string $key): FacebookAccount
    {
        $eFacebookAccount = $this
            -> model
            -> newInstance();

        $eFacebookAccount -> user_id = $userId;
        $eFacebookAccount -> key = $key;

        $eFacebookAccount -> save();

        $facebookAccount = new FacebookAccount();
        $facebookAccount -> user_id = $eFacebookAccount -> user_id;
        $facebookAccount -> key = $eFacebookAccount -> key;
        $facebookAccount -> created_at = $eFacebookAccount -> created_at;
        $facebookAccount -> updated_at = $eFacebookAccount -> updated_at;

        return $facebookAccount;
    }

    public function getByKey(string $key): FacebookAccount
    {
        try {
            $eFacebookAccount = $this
                -> model
                -> where('key', '=', $key)
                -> firstOrFail();

            $facebookAccount = new FacebookAccount();
            $facebookAccount -> id = $eFacebookAccount -> id;
            $facebookAccount -> user_id = $eFacebookAccount -> user_id;
            $facebookAccount -> key = $eFacebookAccount -> key;
            $facebookAccount -> created_at = $eFacebookAccount -> created_at;
            $facebookAccount -> updated_at = $eFacebookAccount -> updated_at;

            return $facebookAccount;
        } catch (ModelNotFoundException $ex) {
            throw new RecordNotFoundException();
        }
    }
}
