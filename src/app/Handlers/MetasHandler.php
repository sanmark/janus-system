<?php

namespace App\Handlers;

use App\API\Validators\Contracts\IMetasValidator;
use App\Models\Meta;
use App\Repos\Contracts\IMetasRepo;
use App\Repos\Exceptions\RecordNotFoundException;

class MetasHandler
{
    private $authSessionsHandler;
    private $metaKeysHandler;
    private $metasRepo;
    private $metasValidator;
    private $usersHandler;

    public function __construct(
    AuthSessionsHandler $authSessionsHandler,
        IMetasRepo $metasRepo,
        IMetasValidator $metasValidator,
        MetaKeysHandler $metaKeysHandler,
        UsersHandler $usersHandler
    ) {
        $this -> authSessionsHandler = $authSessionsHandler;
        $this -> metaKeysHandler = $metaKeysHandler;
        $this -> metasRepo = $metasRepo;
        $this -> metasValidator = $metasValidator;
        $this -> usersHandler = $usersHandler;
    }

    public function getAllByUserId(int $userId): array
    {
        $user = $this
            -> usersHandler
            -> get($userId);

        $metas = $this
            -> metasRepo
            -> getAllByUserId($user -> id);

        return $metas;
    }

    public function getOneByUserIdAndMetaKeyKey(int $userId, string $metaKey): Meta
    {
        $meta = $this
            -> metasRepo
            -> getOneByUserIdAndMetaKey($userId, $metaKey);

        return $meta;
    }

    public function createByUserIdAndMetaKeyKey(int $userId, string $metaKeyKey, $value)
    {
        $data = [];
        $data[ 'value' ] = $value;

        $user = $this
            -> usersHandler
            -> get($userId);

        $metaKey = $this
            -> metaKeysHandler
            -> getByKey($metaKeyKey);

        $this
            -> metasValidator
            -> createByUserIdAndMetaKey($data);

        try {
            $meta = $this -> getOneByUserIdAndMetaKeyKey($userId, $metaKeyKey);

            $updatedMeta = $this -> update($meta -> id, $value);

            return $updatedMeta;
        } catch (RecordNotFoundException $ex) {
            $meta = $this
                -> metasRepo
                -> create($user -> id, $metaKey -> id, $value);

            return $meta;
        }
    }

    private function update(int $metaId, string $value): Meta
    {
        $meta = $this
            -> metasRepo
            -> update($metaId, $value);

        return $meta;
    }
}
