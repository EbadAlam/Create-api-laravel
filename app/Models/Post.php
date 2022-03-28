<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $table = 'post';
    protected $guarded = [''];

     public function comments()
    {
        return $this->hasMany(Comment::class,'comment_post_id', 'id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class,'post_category_id','id');
    }
}
