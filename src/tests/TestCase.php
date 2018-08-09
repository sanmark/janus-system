<?php

namespace Tests;

use App\API\Constants\Headers\RequestHeaderConstants;
use Faker\Factory as Faker;
use Faker\Generator as FakerGenerator;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Mockery;
use function app;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    private $faker;
    private $hasher;

    protected function setUp()
    {
        parent::setUp();

        $this -> artisan('migrate');

        $this -> faker = Faker::create();
        $this -> hasher = app(Hasher::class);
    }

    protected function tearDown()
    {
        $this -> artisan('migrate:reset');

        parent::tearDown();
    }

    public function getWithInvalidAppKeyAndSecretHash($uri, array $headers = [])
    {
        $headers = $this -> attachInvalidAppKeyAndSecretHashToHeadersArray($headers);

        return parent::get($uri, $headers);
    }

    public function getWithValidAppKeyAndInvalidSecretHash($uri, array $headers = [])
    {
        $headers = $this -> attachValidAppKeyAndInvalidSecretHashToHeadersArray($headers);

        return parent::get($uri, $headers);
    }

    public function getWithValidAppKeyAndSecretHash($uri, array $headers = [])
    {
        $headers = $this -> attachValidAppKeyAndSecretHashToHeadersArray($headers);

        return parent::get($uri, $headers);
    }

    public function patchWithInvalidAppKeyAndSecretHash($uri, array $data = [], array $headers = [])
    {
        $headers = $this -> attachInvalidAppKeyAndSecretHashToHeadersArray($headers);

        return parent::patch($uri, $data, $headers);
    }

    public function patchWithValidAppKeyAndInvalidSecretHash($uri, array $data = [], array $headers = [])
    {
        $headers = $this -> attachValidAppKeyAndInvalidSecretHashToHeadersArray($headers);

        return parent::patch($uri, $data, $headers);
    }

    public function patchWithValidAppKeyAndSecretHash($uri, array $data = [], array $headers = [])
    {
        $headers = $this -> attachValidAppKeyAndSecretHashToHeadersArray($headers);

        return parent::patch($uri, $data, $headers);
    }

    public function postWithInvalidAppKeyAndSecretHash($uri, array $data = [], array $headers = [])
    {
        $headers = $this -> attachInvalidAppKeyAndSecretHashToHeadersArray($headers);

        return parent::post($uri, $data, $headers);
    }

    public function postWithValidAppKeyAndInvalidSecretHash($uri, array $data = [], array $headers = [])
    {
        $headers = $this -> attachValidAppKeyAndInvalidSecretHashToHeadersArray($headers);

        return parent::post($uri, $data, $headers);
    }

    public function postWithValidAppKeyAndSecretHash($uri, array $data = [], array $headers = [])
    {
        $headers = $this -> attachValidAppKeyAndSecretHashToHeadersArray($headers);

        return parent::post($uri, $data, $headers);
    }

    protected function faker(): FakerGenerator
    {
        return $this -> faker;
    }

    protected function mock(string $className, array $constructorArgs = null)
    {
        if (is_null($constructorArgs)) {
            return Mockery::mock($className);
        }

        return Mockery::mock($className, $constructorArgs);
    }

    protected function seedDb()
    {
        $this -> artisan('db:seed');
    }

    private function attachInvalidAppKeyAndSecretHashToHeadersArray(array $headers = [])
    {
        $hash = $this
            -> hasher
            -> make('invalid-secret');

        $headers[ RequestHeaderConstants::APP_KEY ] = 'invalid-key';
        $headers[ RequestHeaderConstants::APP_SECRET_HASH ] = $hash;

        return $headers;
    }

    private function attachValidAppKeyAndInvalidSecretHashToHeadersArray(array $headers = [])
    {
        $hash = $this
            -> hasher
            -> make('invalid-secret');

        $headers[ RequestHeaderConstants::APP_KEY ] = 'key';
        $headers[ RequestHeaderConstants::APP_SECRET_HASH ] = $hash;

        return $headers;
    }

    private function attachValidAppKeyAndSecretHashToHeadersArray(array $headers = [])
    {
        $hash = $this
            -> hasher
            -> make('secret');

        $headers[ RequestHeaderConstants::APP_KEY ] = 'key';
        $headers[ RequestHeaderConstants::APP_SECRET_HASH ] = $hash;

        return $headers;
    }
}
