<?php

namespace App\Rules ;

use Illuminate\Contracts\Validation\Rule ;

class MetaKeyRule implements Rule
{

	/**
	 * Create a new rule instance.
	 *
	 * @return void
	 */
	public function __construct ()
	{
		//
	}

	/**
	 * Determine if the validation rule passes.
	 *
	 * @param  string  $attribute
	 * @param  mixed  $value
	 * @return bool
	 */
	public function passes ( $attribute , $value )
	{
		$metaKey = \App\Repos\Concretes\Eloquent\Models\MetaKey::where ( 'key' , $attribute ) -> first () ;
		if ( $metaKey == null )
		{
			return false ;
		}
		return true ;
	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message ()
	{
		return 'The metakey :attribute not exists.' ;
	}

}
