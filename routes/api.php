<?php


use App\Http\Controllers\api\v1\CategoryController;
use App\Http\Controllers\api\v1\CommentController;
use App\Http\Controllers\api\v1\DashboardController;
use App\Http\Controllers\api\v1\PostController;
use App\Http\Controllers\api\v1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login',[UserController::class,'login'])->name('frontend_login');
Route::middleware('auth:api')->group(function() {
	Route::resource('users', UserController::class)->except([
		'create','edit',
	]);
	Route::resource('category',CategoryController::class)->except([
		'create','edit',
	]);

	Route::get('/comments/{post_id}',[CommentController::class,'index'])->name('comments');
	Route::post('/comment',[CommentController::class,'store'])->name('comment_post');
	Route::put('/comment/approve/{comment_id}',[CommentController::class,'comment_approve'])->name('comment_approve');
	Route::put('/comment/unapprove/{comment_id}',[CommentController::class,'comment_unapprove'])->name('comment_unapprove');
	Route::delete('/comment/delete/{comment_id}',[CommentController::class,'destroy'])->name('commnet_delete');

	Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');

	Route::resource('post',PostController::class)->except([
		'create','edit',
	]);
});
