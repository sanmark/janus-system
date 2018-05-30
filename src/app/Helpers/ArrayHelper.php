<?php

namespace App\Helpers ;

class ArrayHelper
{

	public function onlyNonEmptyMembers ( array $input ): array
	{
		$output = [] ;

		foreach ( $input as $key => $value )
		{
			$isGood = (
				! is_null ( $value ) &&
				! empty ( $value )
				) ;

			if ( $isGood )
			{
				$output[ $key ] = $value ;
			}
		}

		return $output ;
	}

}
