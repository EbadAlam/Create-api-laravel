<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
	/**
     *
     * @OA\GET(
     *      path="/api/dashboard",
     *      operationId="getCountList",
     *      tags={"Dashboard"},
     *      security={
     *       {"passport": {}},
     *      },
     *      summary="listing of all counts of post,categories,comments,users",
     *      description="Returns list of post,categories,comments,users",
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
    	$post_count = Post::get()->count();
    	$comment_count = Comment::get()->count();
    	$category_count = Category::get()->count();
    	$user_count = User::get()->count();

    	$data = [
    		'post_count' => $post_count,
    		'comment_count' => $comment_count,
    		'category_count' => $category_count,
    		'user_count' => $user_count,
    	];
    	return $this->sendData($data,'Counts get successfully!');
    }
}
