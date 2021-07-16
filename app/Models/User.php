<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static get(string $string)
 */
class User extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
      'password', 'remember_token',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'owner_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'owner_id');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'owner_id');
    }

    public function  isAdmin()
    {
        return $this->role_id === 1;
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'author_id');
    }

}
