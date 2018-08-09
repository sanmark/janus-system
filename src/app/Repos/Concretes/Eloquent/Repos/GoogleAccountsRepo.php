<?php

namespace App\Repos\Concretes\Eloquent\Repos;

use App\Models\GoogleAccount;
use App\Repos\Concretes\Eloquent\Models\GoogleAccount as eGoogleAccount;
use App\Repos\Contracts\IGoogleAccountsRepo;
use App\Repos\Exceptions\RecordNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GoogleAccountsRepo implements IGoogleAccountsRepo
{
    private $model;

    public function __construct(
    eGoogleAccount $eGoogleAccount
    ) {
        $this -> model = $eGoogleAccount;
    }

    public function create(int $userId, string $key): GoogleAccount
    {
        $eGoogleAccount = $this
            -> model
            -> newInstance();

        $eGoogleAccount -> user_id = $userId;
        $eGoogleAccount -> key = $key;

        $eGoogleAccount -> save();

        $googleAccount = new GoogleAccount();
        $googleAccount -> user_id = $eGoogleAccount -> user_id;
        $googleAccount -> key = $eGoogleAccount -> key;
        $googleAccount -> created_at = $eGoogleAccount -> created_at;
        $googleAccount -> updated_at = $eGoogleAccount -> updated_at;

        return $googleAccount;
    }

    public function get(int $id): GoogleAccount
    {
        try {
            $eGoogleAccount = $this
                -> model
                -> findOrFail($id);

            $googleAccount = new GoogleAccount();
            $googleAccount -> id = $eGoogleAccount -> id;
            $googleAccount -> user_id = $eGoogleAccount -> user_id;
            $googleAccount -> key = $eGoogleAccount -> key;
            $googleAccount -> created_at = $eGoogleAccount -> created_at;
            $googleAccount -> updated_at = $eGoogleAccount -> updated_at;

            return $googleAccount;
        } catch (ModelNotFoundException $ex) {
            throw new RecordNotFoundException();
        }
    }

    public function getByKey(string $key): GoogleAccount
    {
        try {
            $eGoogleAccount = $this
                -> model
                -> where('key', '=', $key)
                -> firstOrFail();

            $googleAccount = new GoogleAccount();
            $googleAccount -> id = $eGoogleAccount -> id;
            $googleAccount -> user_id = $eGoogleAccount -> user_id;
            $googleAccount -> key = $eGoogleAccount -> key;
            $googleAccount -> created_at = $eGoogleAccount -> created_at;
            $googleAccount -> updated_at = $eGoogleAccount -> updated_at;

            return $googleAccount;
        } catch (ModelNotFoundException $ex) {
            throw new RecordNotFoundException();
        }
    }
}
