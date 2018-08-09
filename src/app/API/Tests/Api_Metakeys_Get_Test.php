<?php

namespace App\API\Tests;

use App\Repos\Concretes\Eloquent\Models\App;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * @codeCoverageIgnore
 */
class Api_Metakeys_Get_Test extends TestCase
{
    use DatabaseMigrations;

    public function test_getAllMetakeys_ok()
    {
        $this -> seedDb();

        $this
            -> getWithValidAppKeyAndSecretHash('api/metakeys')
            -> assertStatus(200)
            -> assertJson([
                'data' => [
                    [
                        'id' => 1 ,
                        'key' => 'demo-meta-1' ,
                        'created_at' => null ,
                        'updated_at' => null ,
                    ] ,
                    [
                        'id' => 2 ,
                        'key' => 'demo-meta-2' ,
                        'created_at' => null ,
                        'updated_at' => null ,
                    ] ,
                ] ,
            ]);
    }

    public function test_getAllMetaKeys_rejectsNoAppKey()
    {
        $this -> seedDb();

        $this
            -> get('api/metakeys')
            -> assertStatus(401)
            -> assertJson([
                'errors' => [] ,
            ]);
    }

    public function test_getAllMetaKeys_rejectsInvalidAppKey()
    {
        $this -> seedDb();

        $this
            -> getWithInvalidAppKeyAndSecretHash('api/metakeys')
            -> assertStatus(401)
            -> assertJson([
                'errors' => [] ,
            ]);
    }

    public function test_getAllMetaKeys_rejectsInvalidSecretHash()
    {
        $this -> seedDb();

        $this
            -> getWithValidAppKeyAndInvalidSecretHash('api/metakeys')
            -> assertStatus(401)
            -> assertJson([
                'errors' => [] ,
            ]);
    }
}
