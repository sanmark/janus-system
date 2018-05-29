<?php

namespace App\SystemSettings\Contracts ;

interface ISystemSettingsInterface
{

	public function getAuthSessionActiveMinutes (): int ;
}
