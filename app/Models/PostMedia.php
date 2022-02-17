<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostMedia extends Model
{
    use HasFactory ,SoftDeletes;
    protected $guarded = [];
    protected $fillable = [
        'type',
        'media',
        'post_id'
    ];
    public function post(){
        return $this->belongsTo(Post::class);
    }

    public function getMediaAttribute($value)
    {
        return url('storage/'.$value);
    }
}
