<?php

namespace App\Models ;

abstract class Base
{

	public function toArray (): array
	{
		return get_object_vars ( $this ) ;
	}

	public function toArrayOnly ( array $onlyKeys ): array
	{
		$only = [] ;

		$data = $this -> toArray () ;

		foreach ( $onlyKeys as $onlyKey )
		{
			if ( array_key_exists ( $onlyKey , $data ) )
			{
				$only[ $onlyKey ] = $data[ $onlyKey ] ;
			}
		}

		return $only ;
	}

}
