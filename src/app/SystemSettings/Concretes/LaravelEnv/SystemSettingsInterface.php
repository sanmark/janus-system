<?php

namespace App\SystemSettings\Concretes\LaravelEnv ;

use App\SystemSettings\Contracts\ISystemSettingsInterface ;

class SystemSettingsInterface implements ISystemSettingsInterface
{

	public function getAuthSessionActiveMinutes (): int
	{
		$authSessionActiveMinutes = intval ( config ( 'system.' . Constants::systemAuthSessionActiveMinutes ) ) ;

		return $authSessionActiveMinutes ;
	}

}
