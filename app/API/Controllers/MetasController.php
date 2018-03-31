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
	private $metasHandler ;

	public function __construct (
	MetaKeysHandler $metaKeysHandler
	, MetasHandler $metasHandler
	)
	{
		$this -> metaKeysHandler = $metaKeysHandler ;
		$this -> metasHandler = $metasHandler ;
	}

	public function all ()
	{
		$metaKeys = $this -> metaKeysHandler -> all () ;
		$response = new SuccessResponse ( $metaKeys ) ;
		return $response -> getResponse () ;
	}

	public function create ( Request $request )
	{
		try
		{
			$sessionKey = $request -> header ( RequestHeaderConstants::SESSION_KEY , '' ) ;

			$data = $request -> all () ;

			$meta = $this
				-> metasHandler
				-> createBySessionKey ( $sessionKey , $data ) ;

			$response = new SuccessResponse ( $meta ) ;

			return $response -> getResponse () ;
		} catch ( InvalidInputException $ex )
		{
			$response = new ErrorResponse ( $ex -> getErrors () , 400 ) ;

			return $response -> getResponse () ;
		} catch ( RecordNotFoundException $ex )
		{
			$response = new ErrorResponse ( [] , 401 ) ;
			return $response -> getResponse () ;
		}
	}

	public function get ( Request $request )
	{
		try
		{
			$sessionKey = $request -> header ( RequestHeaderConstants::SESSION_KEY , '' ) ;

			$metas = $this
				-> metasHandler
				-> getAllBySessionKey ( $sessionKey ) ;

			$response = new SuccessResponse ( $metas ) ;

			return $response -> getResponse () ;
		} catch ( RecordNotFoundException $ex )
		{
			$response = new ErrorResponse ( [] , 401 ) ;
			return $response -> getResponse () ;
		}
	}

	public function getMeta ( Request $request , $metaKey )
	{
		try
		{
			$sessionKey = $request -> header ( RequestHeaderConstants::SESSION_KEY , '' ) ;
			$data = $this -> metaKeysHandler -> getMetaForKey ( $sessionKey , $metaKey ) ;
			if ( $data == null )
			{
				$response = new ErrorResponse ( [] , 404 ) ;
				return $response -> getResponse () ;
			}
			$response = new SuccessResponse ( $data ) ;
			return $response -> getResponse () ;
		} catch ( \Exception $ex )
		{
			$response = new ErrorResponse ( [] , 401 ) ;
			return $response -> getResponse () ;
		}
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
