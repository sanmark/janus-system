<?php

namespace App\Handlers;

use App\Models\AuthSession;
use App\Models\GoogleAccount;
use App\Models\User;
use App\Repos\Contracts\IGoogleAccountsRepo;
use App\Repos\Exceptions\RecordNotFoundException;
use App\SystemSettings\Concretes\LaravelEnv\Constants;
use Carbon\Carbon;
use Google_Client;
use GuzzleHttp\Client;
use Illuminate\Contracts\Hashing\Hasher;
use function config;

class GoogleAccountsHandler
{
    private $authSessionsHandler;
    private $carbon;
    private $googleAccountsRepo;
    private $hash;
    private $usersHandler;

    public function __construct(
    AuthSessionsHandler $authSessionsHandler,
        Carbon $carbon,
        IGoogleAccountsRepo $googleAccountsRepo,
        Hasher $hasher,
        UsersHandler $usersHandler
    ) {
        $this -> authSessionsHandler = $authSessionsHandler;
        $this -> carbon = $carbon;
        $this -> hash = $hasher;
        $this -> googleAccountsRepo = $googleAccountsRepo;
        $this -> usersHandler = $usersHandler;
    }

    public function create(string $key): GoogleAccount
    {
        $userKey = $this -> generateUserKeyFromKey($key);
        $userSecret = $this -> hash -> make($this -> carbon -> now());

        $user = $this
            -> usersHandler
            -> create($userKey, $userSecret);

        $googleAccount = $this
            -> googleAccountsRepo
            -> create($user -> id, $key);

        return $googleAccount;
    }

    public function get(int $id): GoogleAccount
    {
        $googleAccount = $this
            -> googleAccountsRepo
            -> get($id);

        return $googleAccount;
    }

    public function getAuthSession(string $key): AuthSession
    {
        $googleAccount = null;

        try {
            $googleAccount = $this -> getByKey($key);
        } catch (RecordNotFoundException $ex) {
            $googleAccount = $this -> create($key);
        }

        $user = $this
            -> usersHandler
            -> get($googleAccount -> user_id);

        $authSession = $this
            -> authSessionsHandler
            -> createForUserObject($user);

        return $authSession;
    }

    public function getAuthSessionByToken(string $token): AuthSession
    {
        $onlineGoogleAccount = $this
            -> getOnlineGoogleAccountByToken($token);

        $email = $onlineGoogleAccount[ 'email' ];

        $authSession = $this
            -> getAuthSession($email);

        return $authSession;
    }

    public function getByKey(string $key): GoogleAccount
    {
        $googleAccount = $this
            -> googleAccountsRepo
            -> getByKey($key);

        return $googleAccount;
    }

    public function getOnlineGoogleAccountByToken(string $token): array
    {
        $googleClient = new Google_Client([
            'client_id' => config('third-party' . Constants::thirdPartyGoogleApiClientId) ,
        ]);

        $guzzleHttpClient = new Client([
            'verify' => config('third-party.' . Constants::thirdPartyGuzzleHttpVerify) ,
        ]);

        $googleClient -> setHttpClient($guzzleHttpClient);

        $account = $googleClient -> verifyIdToken($token);

        if (! $account) {
            throw new RecordNotFoundException();
        }

        return $account;
    }

    public function getUserByKey(string $key): User
    {
        $googleAccount = $this -> getByKey($key);

        $user = $this
            -> usersHandler
            -> get($googleAccount -> user_id);

        return $user;
    }

    private function generateUserKeyFromKey(string $key): string
    {
        /**
         * The key by Google is an email address. So we can use the username
         * part of it and append some random number to it.
         */
        $keyExplodedByAtSymbol = explode('@', $key);
        $googleUsername = $keyExplodedByAtSymbol[ 0 ];
        $randomNumber = rand(1, 9999);

        $userKey = $googleUsername . $randomNumber;

        return $userKey;
    }
}
