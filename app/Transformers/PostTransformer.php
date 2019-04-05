<?php
namespace App\Transformers;

use League\Fractal;

class PostTransformer extends Fractal\TransformerAbstract
{
	public function transform(\App\Post $post)
	{
	    return [
	        'id'=> encrypt($post->id),
    		'title'=> $post->title,
    		'body'=> $post->body,
    		'created_at'=> $post->created_at->diffForHumans(),
    		'updated_at'=> $post->updated_at->diffForHumans(),
    		'created_by'=>[
    			'id'=> encrypt($post->user->id),
    			'name'=> $post->user->name,
    			'email'=> $post->user->email
    		]
	    ];
	}
}