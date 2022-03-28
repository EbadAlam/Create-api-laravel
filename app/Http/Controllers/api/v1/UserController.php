<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Validator;
use Hash;

class UserController extends Controller
{
	    /**
     * @OA\Post(
     ** path="/api/login",
     *   tags={"Login"},
     *   summary="user login",
     *   operationId="UserLogin",
     *
     *  @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *
     *    @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function login(Request $request)
    {
    	$validator = Validator::make($request->all(),[
    		'email' => 'required|email',
    		'password' => 'required',
    	]);
    	if ($validator->fails()) {
    		return $this->sendError($validator->errors(),"Something went wrong!");
    	}
    	$credentials = $request->only('email','password');
    	$login = Auth::attempt($credentials);
    	if ($login) {
    		$user = Auth::user();
    		$token = $user->createToken('MyApp')->accessToken;
    		$data = [
    			'token' => $token,
    			'user' => new UserResource($user),
    		];
    		return $this->sendData($data,'User is logged in successfully!');
    	} else {
    		return $this->sendError('Unauthorized','Something went wrong!');
    	}
    }
    /**
     *
     * @OA\GET(
     *      path="/api/users",
     *      operationId="getUserList",
     *      tags={"User"},
     *      security={
     *       {"passport": {}},
     *      },
     *      summary="listing of all users",
     *      description="Returns list of users",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     *
     */
    public function index()
    {
    	$users = UserResource::collection(User::get());
    	return $this->sendData($users,'Users get successfully!');
    }
    /**
     *
     * @OA\POST(
     *      path="/api/users",
     *      operationId="StoreUser",
     *      tags={"User"},
     *      security={
     *       {"passport": {}},
     *      },
     *      summary="store user",
     *      description="Returns stored user.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *   @OA\Parameter(
     *      name="username",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *
     *    @OA\Parameter(
     *      name="user_firstname",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *    @OA\Parameter(
     *      name="user_lastname",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *
     *  @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *    @OA\Parameter(
     *      name="user_role",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     *
     */    
    public function store(Request $request)
    {
    	$validator = Validator::make($request->all(),[
    		'username' => 'required',
    		'user_firstname' => 'required',
    		'user_lastname' => 'required',
    		'email' => 'required|email',
    		'password' => 'required',
    		'user_role' => 'required',
    	]);
    	if ($validator->fails()) {
    		return $this->sendError($validators->errors(),'Something went wrong!');
    	}
    	$imageName = '';
    	if ($request->user_image) {
            $imageName = "user_images/" .time().'.'.$request->user_image->extension();  
            $request->user_image->move(public_path('user_images'), $imageName);
        }

        $user = User::create([
        	"username" => $request->username,
        	"user_firstname" => $request->user_firstname,
        	"user_lastname" => $request->user_lastname,
        	"email" => $request->email,
        	"password" => Hash::make($request->password),
        	"user_role" => $request->user_role,
        	"user_image" => $request->imageName,
        ]);
        $token = $user->createToken('MyApp')->accessToken;
        $data = [
        	'token' => $token,
        	'data' => new UserResource($user),
        ];
        return $this->sendData($data,'User added successfully!');
    }
    /**
     *
     * @OA\GET(
     *      path="/api/users/{user_id}",
     *      operationId="getUserById",
     *      tags={"User"},
     *      security={
     *       {"passport": {}},
     *      },
     *      summary="get user by id",
     *      description="Returns user by id",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  @OA\Parameter(
     *      name="user_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     *
     */
    public function show(Request $request,$user_id)
    {
        $user = new UserResource(User::findOrFail($user_id));
        return $this->sendData($user,"User get successfully!");
    }
    /**
     *
     * @OA\PUT(
     *      path="/api/users/{user_id}",
     *      operationId="EditUser",
     *      tags={"User"},
     *      security={
     *       {"passport": {}},
     *      },
     *      summary="edit user",
     *      description="Returns edited user.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
      @OA\Parameter(
     *      name="user_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="username",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *
     *    @OA\Parameter(
     *      name="user_firstname",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *    @OA\Parameter(
     *      name="user_lastname",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *
     *  @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *    @OA\Parameter(
     *      name="user_role",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     *
     */  
    public function update(Request $request,$user_id)
    {
        $user = User::findOrFail($user_id);
        $validator = Validator::make($request->all(),[
            'username' => 'required',
            'user_firstname' => 'required',
            'user_lastname' => 'required',
            'email' => 'required|email',
            'user_role' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validators->errors(),'Something went wrong!');
        }
        $imageName = $user->user_image;
        if ($request->user_image) {
            $imageName = "user_images/" .time().'.'.$request->user_image->extension();  
            $request->user_image->move(public_path('user_images'), $imageName);
        }
        $user->update([
            "username" => $request->username,
            "user_firstname" => $request->user_firstname,
            "user_lastname" => $request->user_lastname,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "user_role" => $request->user_role,
            "user_image" => $request->imageName,
        ]);
        $data = [
            'data' => new UserResource($user),
        ];
        return $this->sendData($data,"This user $user->username updated successfully!");
    }
    /**
     *
     * @OA\DELETE(
     *      path="/api/users/{user_id}",
     *      operationId="DeleteUser",
     *      tags={"User"},
     *      security={
     *       {"passport": {}},
     *      },
     *      summary="delete user",
     *      description="Returns deleted user.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *   @OA\Parameter(
     *      name="user_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     *
     */
    public function destroy(Request $request,$user_id)
    {
        $user = User::findOrFail($user_id);
        $user->delete();
        // unlink($user->user_image);
        $data = [
            'data' => new UserResource($user),
        ];
        return $this->sendData($data,"This user $user->username has deleted successfully!");
    }
}
