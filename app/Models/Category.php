<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'category';
    protected $guarded = [''];

    public function posts()
    {
        return $this->hasMany(Post::class,'post_category_id','id');
    }
}
