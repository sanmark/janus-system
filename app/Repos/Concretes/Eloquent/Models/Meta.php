<?php

namespace App\Repos\Concretes\Eloquent\Models ;

use Illuminate\Database\Eloquent\Model ;

class Meta extends Model
{

	protected $fillable = [
		'value' ,
		'user_id' ,
		'meta_key_id'
		] ;

	public function metaKey ()
	{
		return $this -> belongsTo ( MetaKey::class ) ;
	}

}
