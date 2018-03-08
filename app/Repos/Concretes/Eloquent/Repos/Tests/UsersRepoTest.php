<?php

namespace App\Repos\Concretes\Eloquent\Repos\Tests ;

use App\Models\User ;
use App\Repos\Concretes\Eloquent\Models\User as eUser ;
use App\Repos\Concretes\Eloquent\Repos\UsersRepo ;
use App\Repos\Exceptions\RecordNotFoundException ;
use App\Repos\Exceptions\UniqueConstraintFailureException ;
use Exception ;
use Illuminate\Database\Eloquent\ModelNotFoundException ;
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

			throw $ex ;
		}
	}

	public function testGetByKeyOk ()
	{
		$mockEUser = Mockery::mock ( eUser::class ) ;

		$mockEUser
			-> shouldReceive ( 'where' )
			-> withArgs ( [
				'key' ,
				'=' ,
				'rofl' ,
			] )
			-> andReturn ( $mockEUser ) ;

		$mockEUser
			-> shouldReceive ( 'firstOrFail' )
			-> andReturn ( $mockEUser ) ;

		$attributeValues = [
			'id' => 'the_id' ,
			'key' => 'the_key' ,
			'secret' => 'the_secret' ,
			'deleted_at' => 'the_deleted_at' ,
			'created_at' => 'the_created_at' ,
			'updated_at' => 'the_updated_at' ,
			] ;

		foreach ( $attributeValues as $attribute => $value )
		{
			$mockEUser
				-> shouldReceive ( 'getAttribute' )
				-> withArgs ( [ $attribute ] )
				-> andReturn ( $value ) ;
		}

		$usersRepo = new UsersRepo ( $mockEUser ) ;

		$user = $usersRepo
			-> getByKey ( 'rofl' ) ;

		$this -> assertInstanceOf ( User::class , $user ) ;
		$this -> assertSame ( $user -> id , 'the_id' ) ;
		$this -> assertSame ( $user -> key , 'the_key' ) ;
		$this -> assertSame ( $user -> secret , 'the_secret' ) ;
		$this -> assertSame ( $user -> deleted_at , 'the_deleted_at' ) ;
		$this -> assertSame ( $user -> created_at , 'the_created_at' ) ;
		$this -> assertSame ( $user -> updated_at , 'the_updated_at' ) ;
	}

	public function testGetByKeyThrowsRecordNotFoundException ()
	{
		$this -> expectException ( RecordNotFoundException::class ) ;

		$mockEUser = Mockery::mock ( eUser::class ) ;

		$mockEUser
			-> shouldReceive ( 'where' )
			-> withArgs ( [
				'key' ,
				'=' ,
				'rofl' ,
			] )
			-> andThrow ( ModelNotFoundException::class ) ;

		$usersRepo = new UsersRepo ( $mockEUser ) ;

		$user = $usersRepo
			-> getByKey ( 'rofl' ) ;
	}

}
