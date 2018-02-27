<?php

namespace Tests ;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase ;
use Faker\Factory as Faker ;
use Faker\Generator as FakerGenerator ;

abstract class TestCase extends BaseTestCase
{

	use CreatesApplication ;

	private $faker ;

	protected function faker (): FakerGenerator
	{
		return $this -> faker ;
	}

	protected function setUp ()
	{
		parent::setUp () ;

		$this -> artisan ( 'migrate' ) ;

		$this -> faker = Faker::create () ;
	}

	protected function tearDown ()
	{
		$this -> artisan ( 'migrate:reset' ) ;

		parent::tearDown () ;
	}

}
