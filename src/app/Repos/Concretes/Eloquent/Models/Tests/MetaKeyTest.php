<?php

namespace App\Repos\Concretes\Eloquent\Models\Tests;

use App\Repos\Concretes\Eloquent\Models\Meta;
use App\Repos\Concretes\Eloquent\Models\MetaKey;
use Tests\TestCase;

/**
 * @codeCoverageIgnore
 */
class MetaKeyTest extends TestCase
{
    public function test_metas_ok()
    {
        $metaKey = $this -> mock(MetaKey::class . '[hasMany]');

        $metaKey -> shouldReceive('hasMany')
            -> withArgs([
                Meta::class ,
            ])
            -> andReturn(149);

        $response = $metaKey -> metas();

        $this -> assertEquals(149, $response);
    }
}
