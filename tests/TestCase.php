<?php

namespace Tests ;

use Faker\Factory as Faker ;
use Faker\Generator as FakerGenerator ;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase ;
use Mockery ;

abstract class TestCase extends BaseTestCase
{

	use CreatesApplication ;

	private $faker ;

	protected function faker (): FakerGenerator
	{
		return $this -> faker ;
	}

	protected function mock ( string $className )
	{
		return Mockery::mock ( $className ) ;
	}

	protected function seedDb ()
	{
		$this -> artisan ( 'db:seed' ) ;
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
