<?php

namespace App\SystemSettings\Concretes\LaravelEnv\Tests ;

use App\SystemSettings\Concretes\LaravelEnv\Constants ;
use App\SystemSettings\Concretes\LaravelEnv\SystemSettingsInterface ;
use Tests\TestCase ;
use function config ;
use function dd ;

/**
 * @codeCoverageIgnore
 */
class SystemSettingsInterfaceTest extends TestCase
{

	public function test_getAuthSessionActiveMinutes_ok ()
	{
		$systemSettingsInterface = new SystemSettingsInterface() ;

		config ( [
			'system.' . Constants::systemAuthSessionActiveMinutes => 149 ,
		] ) ;

		$result = $systemSettingsInterface -> getAuthSessionActiveMinutes () ;

		$this -> assertEquals ( 149 , $result ) ;
	}

}
