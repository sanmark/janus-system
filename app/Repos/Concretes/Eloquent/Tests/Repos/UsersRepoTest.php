<?php

namespace App\Repos\Concretes\Eloquent\Tests\Repos ;

use App\Models\User ;
use App\Repos\Concretes\Eloquent\Models\User as eUser ;
use App\Repos\Concretes\Eloquent\Repos\UsersRepo ;
use App\Repos\Exceptions\UniqueConstraintFailureException ;
use Exception ;
use Illuminate\Database\QueryException ;
use Mockery ;
use Tests\TestCase ;

/**
 * @codeCoverageIgnore
 */
class UsersRepoTest extends TestCase
{

	public function testCreateOk ()
	{
		$mockEUser = Mockery::mock ( eUser::class ) ;

		$mockEUser
			-> shouldReceive ( 'newInstance' )
			-> andReturn ( $mockEUser ) ;

		$mockEUser
			-> shouldReceive ( 'setAttribute' ) ;

		$mockEUser
			-> shouldReceive ( 'save' ) ;

		$mockEUser
			-> shouldReceive ( 'getAttribute' ) ;

		$usersRepo = new UsersRepo ( $mockEUser ) ;

		$userKey = $this -> faker () -> userName ;
		$userSecret = $this -> faker () -> password ;

		$user = $usersRepo -> create ( $userKey , $userSecret ) ;

		$this -> assertInstanceOf ( User::class , $user ) ;
	}

	public function testCreateThrowsUniqueConstraintFailureException ()
	{
		$this -> expectException ( UniqueConstraintFailureException::class ) ;

		$mockEUser = Mockery::mock ( eUser::class ) ;
		$mockPreviousException = Mockery::mock ( Exception::class ) ;

		$mockEUser
			-> shouldReceive ( 'newInstance' )
			-> andReturn ( $mockEUser ) ;

		$mockEUser
			-> shouldReceive ( 'setAttribute' ) ;

		$mockEUser
			-> shouldReceive ( 'save' )
			-> andThrow ( new QueryException ( 'sql' , [] , $mockPreviousException ) ) ;

		$userKey = $this -> faker () -> userName ;
		$userSecret = $this -> faker () -> password ;

		$usersRepo = new UsersRepo ( $mockEUser ) ;

		try
		{
			$usersRepo -> create ( $userKey , $userSecret ) ;
		} catch ( UniqueConstraintFailureException $ex )
		{
			$this -> assertSame ( 'user_key' , $ex -> getConstraint () ) ;
			$this -> assertSame ( $userKey , $ex -> getValue () ) ;

			throw $ex ;
		}
	}

}
