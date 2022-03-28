<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Validator;

class PostController extends Controller
{
	/**
     *
     * @OA\GET(
     *      path="/api/post",
     *      operationId="getPostList",
     *      tags={"Post"},
     *      security={
     *       {"passport": {}},
     *      },
     *      summary="listing of all posts",
     *      description="Returns list of posts",
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
    	$posts = PostResource::collection(Post::get());
    	return $this->sendData($posts,'All posts get successfully!');
    }
    /**
     *
     * @OA\POST(
     *      path="/api/post",
     *      operationId="StorePost",
     *      tags={"Post"},
     *      security={
     *       {"passport": {}},
     *      },
     *      summary="store post",
     *      description="Returns stored post.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *   @OA\Parameter(
     *      name="post_category_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *
     *    @OA\Parameter(
     *      name="post_title",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *    @OA\Parameter(
     *      name="post_author",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *
     *  @OA\Parameter(
     *      name="post_date",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="post_content",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *    @OA\Parameter(
     *      name="post_status",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *    @OA\Parameter(
     *      name="post_tags",
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
            'post_category_id' => 'required',
            'post_title' => 'required',
            'post_author' => 'required',
            'post_date' => 'required',
            'post_status' => 'required',
            'post_content' => 'required',
            'post_tags' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),'Something went wrong!');
        }
        $imageName = '';
        if ($request->post_image) {
            $imageName = "post_images/" .time().'.'.$request->post_image->extension();  
            $request->post_image->move(public_path('post_images'), $imageName);
        }
        $post = Post::create([
            'post_category_id' => $request->post_category_id,
            'post_title' => $request->post_title,
            'post_author' => $request->post_author,
            'post_date' => $request->post_date,
            'post_status' => $request->post_status,
            'post_content' => $request->post_content,
            'post_tags' => $request->post_tags,
            'post_image' => $imageName,
        ]);
        $data = [
            'data' => new PostResource($post),
        ];
        return $this->sendData($data,"post added successfully!");
        
    }
    /**
     *
     * @OA\GET(
     *      path="/api/post/{post_id}",
     *      operationId="getPostById",
     *      tags={"Post"},
     *      security={
     *       {"passport": {}},
     *      },
     *      summary="Get post by id",
     *      description="Returns post by id",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *   @OA\Parameter(
     *      name="post_id",
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
    public function show(Request $request,$post_id)
    {
        $post = new PostResource(Post::findOrFail($post_id));
        return $this->sendData($post,'Post get successfully!');
    }
    /**
     *
     * @OA\PUT(
     *      path="/api/post/{post_id}",
     *      operationId="updatePost",
     *      tags={"Post"},
     *      security={
     *       {"passport": {}},
     *      },
     *      summary="update post",
     *      description="Returns updated post.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  @OA\Parameter(
     *      name="post_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="post_category_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     * @OA\Parameter(
     *      name="post_title",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *    @OA\Parameter(
     *      name="post_author",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *
     *  @OA\Parameter(
     *      name="post_date",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="post_content",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *    @OA\Parameter(
     *      name="post_status",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *    @OA\Parameter(
     *      name="post_tags",
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
    public function update(Request $request,$post_id)
    {
        $post = Post::findOrFail($post_id);
        $validator = Validator::make($request->all(),[
            'post_category_id' => 'required',
            'post_title' => 'required',
            'post_author' => 'required',
            'post_date' => 'required',
            'post_status' => 'required',
            'post_content' => 'required',
            'post_tags' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),'Something went wrong!');
        }
        $imageName = $post->post_image;
        if ($request->post_image) {
            $imageName = "post_images/" .time().'.'.$request->post_image->extension();  
            $request->post_image->move(public_path('post_images'), $imageName);
        }
        $post->update([
            'post_category_id' => $request->post_category_id,
            'post_title' => $request->post_title,
            'post_author' => $request->post_author,
            'post_date' => $request->post_date,
            'post_status' => $request->post_status,
            'post_content' => $request->post_content,
            'post_tags' => $request->post_tags,
            'post_image' => $imageName,
        ]);
        $data = [
            'data' => new PostResource($post),
        ]; 
        return $this->sendData($data,"This post $post->post_title updated successfully!");
    } 
    /**
     *
     * @OA\DELETE(
     *      path="/api/post/{post_id}",
     *      operationId="DeletePost",
     *      tags={"Post"},
     *      security={
     *       {"passport": {}},
     *      },
     *      summary="delete post",
     *      description="Returns deleted post.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *   @OA\Parameter(
     *      name="post_id",
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
    public function destroy(Request $request,$post_id)
    {
        $post = Post::findOrFail($post_id);
        $post->delete();
        $data = [
            'data' => new PostResource($post),
        ];
        return $this->sendData($data,"This post $post->post_title deleted successfully!");
    }
}
