<?php

namespace App\SystemSettings\Concretes\LaravelEnv;

use App\SystemSettings\Contracts\ISystemSettingsInterface;
use Illuminate\Support\ServiceProvider;

class LaravelEnvSystemSettingsProvider extends ServiceProvider
{
    public function register()
    {
        $map = [
            ISystemSettingsInterface::class => SystemSettingsInterface::class ,
        ];

        foreach ($map as $abstract => $concrete) {
            $this
                -> app
                -> bind($abstract, $concrete);
        }
    }
}
