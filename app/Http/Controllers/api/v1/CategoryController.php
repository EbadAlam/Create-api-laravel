<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends Controller
{
	/**
     *
     * @OA\GET(
     *      path="/api/category",
     *      operationId="getCategoryList",
     *      tags={"Category"},
     *      security={
     *       {"passport": {}},
     *      },
     *      summary="listing of all categories",
     *      description="Returns list of categories",
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
    	$categories = CategoryResource::collection(Category::get());
    	return $this->sendData($categories,'Categories get successfully!');
    }
    /**
     *
     * @OA\POST(
     *      path="/api/category",
     *      operationId="StoreCategory",
     *      tags={"Category"},
     *      security={
     *       {"passport": {}},
     *      },
     *      summary="store category",
     *      description="Returns stored category.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *   @OA\Parameter(
     *      name="cat_title",
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
    		'cat_title' => 'required',
    	]);
    	if ($validator->fails()) {
    		return $this->sendError($validator->errors(),'Something went wrong!');
    	}
    	$category = Category::create([
    		'cat_title' => $request->cat_title,
    	]);
    	$data = [
    		'data' => new CategoryResource($category),
    	];
    	return $this->sendData($data,'Category created successfully!');
    }
    /**
     *
     * @OA\PUT(
     *      path="/api/category/{category_id}",
     *      operationId="UpdateCategory",
     *      tags={"Category"},
     *      security={
     *       {"passport": {}},
     *      },
     *      summary="update category",
     *      description="Category updated.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *   @OA\Parameter(
     *      name="category_id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="cat_title",
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
    public function update(Request $request,$category_id)
    {
    	$validator = Validator::make($request->all(),[
    		'cat_title' => 'required',
    	]);
    	if ($validator->fails()) {
    		return $this->sendError($validator->errors(),'Something went wrong!');
    	}

    	$category = Category::findOrFail($category_id);
    	$category->update([
    		'cat_title' => $request->cat_title,
    	]);
    	$data = [
    		'data' => new CategoryResource($category),
    	];
    	return $this->sendData($data,'Category updated successfully!');
    }
    /**
     *
     * @OA\DELETE(
     *      path="/api/category/{category_id}",
     *      operationId="DeleteCategory",
     *      tags={"Category"},
     *      security={
     *       {"passport": {}},
     *      },
     *      summary="delete category",
     *      description="Returns deleted category.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *   @OA\Parameter(
     *      name="category_id",
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
    public function destroy(Request $request,$category_id)
    {
    	$category = Category::findOrFail($category_id);
    	$category->delete();
    	return $this->sendData($category,"This category $category->cat_title deleted successfully!");
    }
}
