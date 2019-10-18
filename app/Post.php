<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable=[
    	'title',
    	'body',
    	'tags',
    	'user_id'
    ];

   //  protected $mappingProperties = array(
	  //   'title' => [
	  //     'type' => 'string',
	  //     "analyzer" => "standard",
	  //   ],
	  //   'body' => [
	  //     'type' => 'string',
	  //     "analyzer" => "standard",
	  //   ],
	  //   'tags' => [
	  //     'type' => 'string',
	  //     "analyzer" => "stop",
	  //     "stopwords" => [","]
	  //   ],
	  // );

    public function user()
    {
    	return $this->belongsTo(\App\User::class);
    }
}
