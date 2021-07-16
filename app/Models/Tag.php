<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;

    public $guarded = [];

    public function tasks()
    {
        return $this->morphedByMany(Task::class, 'taggable');
    }

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }

    public function news()
    {
        return $this->morphedByMany(News::class, 'taggable');
    }

    public function users()
    {
        return $this->morphedByMany(User::class, 'taggable');
    }

    public function getRouteKeyName()
    {
        return 'name';
    }

    public static function tagsCloud(callable $filter = null)
    {
        if (!$filter) {
            return (new static())->all();
        }

        return call_user_func($filter, (new static())->query())->get();
    }
}
