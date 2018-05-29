<?php

namespace App\API\Controllers ;

use App\API\Constants\Headers\RequestHeaderConstants ;
use App\API\Responses\ErrorResponse ;
use App\API\Responses\SuccessResponse ;
use App\API\Validators\Exceptions\InvalidInputException ;
use App\Handlers\MetaKeysHandler ;
use App\Handlers\MetasHandler ;
use App\Http\Controllers\Controller ;
use App\Repos\Exceptions\RecordNotFoundException ;
use Illuminate\Http\Request ;

class MetasController extends Controller
{

	private $metaKeysHandler ;

	public function __construct (
	MetaKeysHandler $metaKeysHandler
	, MetasHandler $metasHandler
	)
	{
		$this -> metaKeysHandler = $metaKeysHandler ;
	}

	public function metaValueUsersGet ( string $metaKey , string $metaValue )
	{
		$users = $this
			-> metaKeysHandler
			-> getUsersForMetaValue ( $metaKey , $metaValue ) ;

		$response = new SuccessResponse ( $users ) ;

		return $response -> getResponse () ;
	}

}
