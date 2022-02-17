<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];
    protected $fillable = [
        'description',
        'post_share_id',
        'is_pin',
        'user_id',

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function comment(){
        return $this->hasMany(Comment::class);
    }

    public function share(){
        return $this->belongsTo(Post::class,'post_share_id');
    }

    public function likes()
{
    return $this->belongsToMany('App\User', 'likes');
}
    public function media(){
        return $this->hasMany(PostMedia::class);
    }
 
}
