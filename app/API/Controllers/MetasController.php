<?php

namespace App\API\Controllers ;

use App\API\Constants\Headers\RequestHeaderConstants ;
use App\API\Responses\ErrorResponse ;
use App\API\Responses\SuccessResponse ;
use App\API\Validators\Exceptions\InvalidInputException ;
use App\Handlers\MetaKeysHandler ;
use App\Http\Controllers\Controller ;
use App\Repos\Exceptions\RecordNotFoundException ;
use Illuminate\Http\Request ;
use function app ;

class MetasController extends Controller
{

	private $metaKeysHandler ;

	public function __construct ()
	{
		$this -> metaKeysHandler = app ( MetaKeysHandler::class ) ;
	}

	public function all ()
	{
		$metaKeys = $this -> metaKeysHandler -> all () ;
		$response = new SuccessResponse ( $metaKeys ) ;
		return $response -> getResponse () ;
	}

	public function getMetas ( Request $request )
	{
		try
		{
			$sessionKey = $request -> header ( RequestHeaderConstants::SESSION_KEY , '' ) ;
			$data = $this -> metaKeysHandler -> getAllMetas ( $sessionKey ) ;
			$response = new SuccessResponse ( $data ) ;
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
		} catch ( Exception $ex )
		{
			$response = new ErrorResponse ( [] , 401 ) ;
			return $response -> getResponse () ;
		}
	}

	public function saveMetas ( Request $request )
	{
		try
		{
			$sessionKey = $request -> header ( RequestHeaderConstants::SESSION_KEY , '' ) ;
			$data = $request -> all () ;
			$this -> metaKeysHandler -> saveMetas ( $sessionKey , $data ) ;
			$response = new SuccessResponse ( $this -> metaKeysHandler -> getAllMetas ( $sessionKey ) , 200 ) ;
			return $response -> getResponse () ;
		} catch ( InvalidInputException $ex )
		{
			$response = new ErrorResponse ( $ex -> getErrors () , 400 ) ;
			return $response -> getResponse () ;
		}
	}

	public function getMetasForUser ( $userID )
	{
		try
		{
			$metas = $this -> metaKeysHandler -> getmetasForUser ( $userID ) ;
			$response = new SuccessResponse ( $metas , 200 ) ;
			return $response -> getResponse () ;
		} catch ( RecordNotFoundException $ex )
		{
			$response = new ErrorResponse ( [] , 404 ) ;
			return $response -> getResponse () ;
		}
	}

	public function getMetaForUser ( $userID , $metaKey )
	{
		try
		{
			$meta = $this -> metaKeysHandler -> getmetaForUser ( $userID , $metaKey ) ;
			$response = new SuccessResponse ( $meta , 200 ) ;
			return $response -> getResponse () ;
		} catch ( RecordNotFoundException $ex )
		{
			$response = new ErrorResponse ( [] , 404 ) ;
			return $response -> getResponse () ;
		}
	}

}
