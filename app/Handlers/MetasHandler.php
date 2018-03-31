<?php

namespace App\Handlers ;

use App\API\Constants\Inputs\MetasInputConstants ;
use App\API\Validators\Contracts\IMetasValidator ;
use App\Models\Meta ;
use App\Repos\Contracts\IMetasRepo ;
use function dd ;

class MetasHandler
{

	private $authSessionsHandler ;
	private $metaKeysHandler ;
	private $metasRepo ;
	private $metasValidator ;

	public function __construct (
	AuthSessionsHandler $authSessionsHandler
	, IMetasRepo $metasRepo
	, IMetasValidator $metasValidator
	, MetaKeysHandler $metaKeysHandler
	)
	{
		$this -> authSessionsHandler = $authSessionsHandler ;
		$this -> metaKeysHandler = $metaKeysHandler ;
		$this -> metasRepo = $metasRepo ;
		$this -> metasValidator = $metasValidator ;
	}

	/**
	 * @param array $data['key'] Meta Key's "key".
	 * @param array $data['value'] Meta value.
	 */
	public function createBySessionKey ( string $sessionKey , array $data ): Meta
	{
		$this
			-> metasValidator
			-> create ( $data ) ;

		$authSession = $this
			-> authSessionsHandler
			-> getByKey ( $sessionKey ) ;

		$metaKeyKey = $data[ MetasInputConstants::Key ] ;
		$value = $data[ MetasInputConstants::Value ] ;

		$metaKey = $this
			-> metaKeysHandler
			-> getByKey ( $metaKeyKey ) ;

		$meta = $this
			-> metasRepo
			-> create ( $authSession -> user_id , $metaKey -> id , $value ) ;

		return $meta ;
	}

	public function getAllBySessionKey ( string $sessionKey ): array
	{
		$authSession = $this
			-> authSessionsHandler
			-> getByKey ( $sessionKey ) ;

		$userId = $authSession -> user_id ;

		$metas = $this -> getAllByUserId ( $userId ) ;

		return $metas ;
	}

	public function getAllByUserId ( int $userId ): array
	{
		$metas = $this
			-> metasRepo
			-> getAllByUserId ( $userId ) ;

		return $metas ;
	}

	public function getOneByUserIdAndMetaKey ( int $userId , string $metaKey ): Meta
	{
		$meta = $this
			-> metasRepo
			-> getOneByUserIdAndMetaKey ( $userId , $metaKey ) ;

		return $meta ;
	}

}
