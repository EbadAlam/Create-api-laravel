<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Validator;

class CommentController extends Controller
{
    /**
     * @OA\GET(
     ** path="/api/comments/{post_id}",
     *   tags={"Comment"},
     *   summary="get comments",
     *   operationId="GetComments",
     *      security={
     *       {"passport": {}},
     *      },
     *   @OA\Parameter(
     *      name="post_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
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
    public function index(Request $request,$post_id)
    {
        $post = Post::with('comments','comments.user','comments.post','comments.post.category')->findOrFail($post_id);
        $collectionComments = CommentResource::collection($post->comments);
        return $this->sendData($collectionComments,"Comments get successfully!");
    }
	/**
     *
     * @OA\POST(
     *      path="/api/comment",
     *      operationId="StoreComment",
     *      tags={"Comment"},
     *      security={
     *       {"passport": {}},
     *      },
     *      summary="store comment",
     *      description="Returns stored comment.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *   @OA\Parameter(
     *      name="comment_user_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *
     *    @OA\Parameter(
     *      name="comment_post_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *    @OA\Parameter(
     *      name="comment_content",
     *      in="query",
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
    public function store(Request $request)
    {
    	$validator = Validator::make($request->all(),[
    		'comment_user_id' => 'required',
    		'comment_post_id' => 'required',
    		'comment_content' => 'required',
    	]);
    	if ($validator->fails()) {
    		return $this->sendError($validator->errors(),'Something went wrong!');
    	}
    	$comment = Comment::create([
    		'comment_user_id' => $request->comment_user_id,
    		'comment_post_id' => $request->comment_post_id,
    		'comment_content' => $request->comment_content,
    		'comment_status' => 'unapprove',
    	]);
    	$data = [
    		'data' => new CommentResource($comment),
    	];
    	return $this->sendData($data,'comment add successfully!');
    }
    /**
     *
     * @OA\PUT(
     *      path="/api/comment/approve/{comment_id}",
     *      operationId="statusApprove",
     *      tags={"Comment"},
     *      security={
     *       {"passport": {}},
     *      },
     *      summary="update comment to approve",
     *      description="Returns updated comment to approve.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  @OA\Parameter(
     *      name="comment_id",
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
    public function comment_approve(Request $request,$comment_id)
    {
    	$comment = Comment::findOrfail($comment_id);
    	$comment->update([
    		"comment_status" => 'Approve',
    	]);
    	$data = [
    		'data' => new CommentResource($comment),
    	];
    	return $this->sendData($data,"Status set to Approve of this comment $comment->comment_content");
    }
    /**
     *
     * @OA\PUT(
     *      path="/api/comment/unapprove/{comment_id}",
     *      operationId="statusUnApprove",
     *      tags={"Comment"},
     *      security={
     *       {"passport": {}},
     *      },
     *      summary="update comment to unapprove",
     *      description="Returns updated comment to unapprove.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  @OA\Parameter(
     *      name="comment_id",
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
    public function comment_unapprove(Request $request,$comment_id)
    {
    	$comment = Comment::findOrfail($comment_id);
    	$comment->update([
    		"comment_status" => 'Unapprove',
    	]);
    	$data = [
    		'data' => new CommentResource($comment),
    	];
    	return $this->sendData($data,"Status set to unapprove of this comment $comment->comment_content");
    }
    /**
     *
     * @OA\DELETE(
     *      path="/api/comment/delete/{comment_id}",
     *      operationId="DeleteComment",
     *      tags={"Comment"},
     *      security={
     *       {"passport": {}},
     *      },
     *      summary="delete comment",
     *      description="Returns deleted comment.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *   @OA\Parameter(
     *      name="comment_id",
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
    public function destroy(Request $request,$comment_id)
    {
    	$comment = Comment::findOrfail($comment_id);
    	$comment->delete();
    	$data = [
    		'data' => new CommentResource($comment),
    	];
    	return $this->sendData($data,"Comment deleted successfully!");
    }
}
