<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use App\Traits\CommonTrait;


use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class AuthController extends Controller
{
	use CommonTrait;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function register(Request $request)
    {
        $credentials = $request->only('name', 'email', 'password');
        
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users'
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            return response()->json(['success'=> false, 'error'=> $validator->messages()]);
        }
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;
        
        $user = User::create(['name' => $name, 'email' => $email, 'password' => Hash::make($password)]);

        /*$this->validate($request,[
        	'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users'
        ]);*/

        $user = \App\User::create(['name' => $name, 'email' => $email, 'password' => Hash::make($password)]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
       
        $validator = \Validator::make($credentials, [
		    'email' => 'required|email',
            'password' => 'required',
		]);

        if($validator->fails()) {
            return response()->json(['success'=> false, 'error'=> $validator->messages()], 401);
        }
        
        //$credentials['is_verified'] = 1;
        
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['success' => false, 'error' => 'We cant find an account with this credentials. Please make sure you entered the right information and you have verified your email address.'], 404);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['success' => false, 'error' => 'Failed to login, please try again.'], 500);
        }
        // all good so return the token
        return response()->json(['success' => true, 'data'=> [ 'token' => $token ]], 200);
    }
    

    /**
    * @api {post} /login
    * @apiVersion 1.0.1
    * @apiGroup Authentication
    * @apiName Login
    * @apiDescription Login API after successful login will give the user details
    * @apiParam {String} email Mandatory.
    * @apiParam {String} password Mandatory.
    * @apiSuccessExample {json} Success-Response:
        {
            "name": "Hayley Dibbert",
            "email": "cremin.elza@reichert.com",
            "no_of_post": 0
        }
    * @apiErrorExample {json} Error-Response:
        {
            "message": "Unauthenticated."
        }
   **/

    public function meme(Request $request)
    {
        if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
        }

        $data = [
        	'name'=>$user->name,
        	'email'=>$user->email,
        	'no_of_post'=>$user->post->count()
        ];

        return response()->json($data);
    }

    public function memev2(Request $request)
    {
        $user = $this->getcurrentuser();

        $data = [
        	'name'=>$user->name,
        	'email'=>$user->email,
        	'no_of_post'=>$user->post->count()
        ];
        
        return response()->json($data);
    }

    public function getMyPosts(Request $request)
    {
    	$user = $this->getcurrentuser();
    	$posts = $user->post;
    	return response()->json($posts);
    }


    public function getMyPostsv2(Request $request)
    {
    	$user = $this->getcurrentuser();
    	$limit = 20;
    	if($request->has('limit'))
    		$limit = $request->limit;

    	$posts = \App\Post::paginate($limit);
    	$postdata = [];

    	foreach ($posts as $key => $value) {

    		$tmp['id']=encrypt($value->id);
    		$tmp['title']=$value->title;
    		$tmp['body']=$value->body;
    		$tmp['created_at']=$value->created_at->diffForHumans();
    		$tmp['updated_at']=$value->updated_at->diffForHumans();
    		$tmp['created_by']['id']=encrypt($value->user->id);
    		$tmp['created_by']['name']=$value->user->name;
    		$tmp['created_by']['email']=$value->user->email;

    		$postdata['data'][] = $tmp;
    	}

    	$postdata['data']['perPage'] = $posts->perPage();
    	$postdata['data']['totalRecord'] = $posts->total();
	    $postdata['data']['currentPage'] = $posts->currentPage();
	    $postdata['data']['totalPage'] = $posts->currentPage();
	    $postdata['data']['hasMorePages'] = $posts->hasMorePages();
	    $postdata['data']['nextPage'] = $posts->nextPageUrl(); 
	    $postdata['data']['prevPage'] = $posts->previousPageUrl();

    	//$postdata['meta']=$posts->
    	return response()->json($postdata);
    }

    public function getMyPostsv3(Request $request)
    {
    	$user = $this->getcurrentuser();
    	$limit = 20;
    	if($request->has('limit'))
    		$limit = $request->limit;

    	$paginator = \App\Post::paginate($limit);
	    $posts = $paginator->getCollection();
	    $fractal = new Manager();
		$resource = new Collection($posts, new \App\Transformers\PostTransformer());
		$resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
    	return response()->json($fractal->createData($resource)->toArray());
    }
}
