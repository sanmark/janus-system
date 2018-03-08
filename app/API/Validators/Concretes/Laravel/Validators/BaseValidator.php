<?php

namespace App\API\Validators\Concretes\Laravel\Validators ;

use App\API\Validators\Exceptions\InvalidInputException ;
use function validator ;

abstract class BaseValidator
{

	protected function validate ( array $data , array $rules , array $messages )
	{
		$laravelStyleMessages = $this -> generateLaravelStyleMessages ( $messages ) ;

		$v = validator ( $data , $rules , $laravelStyleMessages ) ;

		if ( $v -> fails () )
		{
			$e = $v
				-> errors ()
				-> toArray () ;

			throw new InvalidInputException ( $e ) ;
		}
	}

	/**
	 * Generate Laravel style validator messages array from a validator message
	 * array style specific to this system.
	 * 
	 * System style:
	 * 
	 * [
	 *     'field_n' => [
	 *         'rule_m' => 'message_m',
	 *         'rule_m+1' => 'message_m+1',
	 *     ],
	 *     'field_n+1' => [
	 *         'rule_l' => 'message_l',
	 *         'rule_l+1' => 'message_l+1',
	 *     ],
	 * ]
	 * 
	 * Laravel Style:
	 * 
	 * [
	 *     'field_n.rule_m' => 'message_m',
	 *     'field_n.rule_m+1' => 'message_m+1',
	 *     'field_n+1.rule_l' => 'message_l',
	 *     'field_n+1.rule_l+1' => 'message_l+1',
	 * ]
	 * 
	 * @param array $messages System style messages array.
	 */
	private function generateLaravelStyleMessages ( array $messages ): array
	{
		$laravelMessages = [] ;

		foreach ( $messages as $field => $rulesAndMessage )
		{
			foreach ( $rulesAndMessage as $rule => $message )
			{
				$fieldRuleCombo = $field . '.' . $rule ;

				$laravelMessages[ $fieldRuleCombo ] = $message ;
			}
		}

		return $laravelMessages ;
	}

}
