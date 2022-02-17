<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable ,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = [];
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'photo',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function comment(){
        return $this->hasMany(Comment::class);
    }
    public function likes()
{
    return $this->hasMany('App\Post', 'likes', 'user_id', 'post_id');
}
    public function notification(){
        return $this->hasMany(Notification::class);
    }

    public function friends()
{
    return $this->belongsToMany(User::class, 'joins', 'user_id', 'friend_id','id','id');
}

    public function linkedSocialAccounts()
    {
        return $this->hasMany(LinkedSocialAccount::class);
    }

    public function getPhotoAttribute($value)
    {
        return url('storage/'.$value);
    }
}
