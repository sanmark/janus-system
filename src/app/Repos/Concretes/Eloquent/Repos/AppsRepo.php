<?php

namespace App\Repos\Concretes\Eloquent\Repos;

use App\Models\App;
use App\Repos\Concretes\Eloquent\Models\App as eApp;
use App\Repos\Contracts\IAppsRepo;
use App\Repos\Exceptions\RecordNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AppsRepo implements IAppsRepo
{
    private $model;

    public function __construct(eApp $eApp)
    {
        $this -> model = $eApp;
    }

    public function getByKey(string $key): App
    {
        try {
            $eApp = $this
                -> model
                -> where('key', '=', $key)
                -> firstOrFail();

            $app = new App();

            $app -> id = $eApp -> id;
            $app -> key = $eApp -> key;
            $app -> secret = $eApp -> secret;
            $app -> created_at = $eApp -> created_at;
            $app -> updated_at = $eApp -> updated_at;

            return $app;
        } catch (ModelNotFoundException $ex) {
            throw new RecordNotFoundException();
        }
    }
}
