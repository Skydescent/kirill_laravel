<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Str;

class News extends Model implements Commentable, Taggable
{

    protected $casts = [
        'published' => 'boolean'
    ];

    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return string
     */
    public function getShortBodyAttribute(): string
    {
        return mb_substr($this->body, 0, 150) . '...';
    }

    public function queryFilter($query)
    {
        return $query->orWhereHas('news', function (Builder $subQuery) {
            $subQuery->where('published', 1);
        });
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug(Str::substr($value, 0, 8), '-');
    }

    public function setSlugAttribute($value) {

        if (static::whereSlug($slug = Str::of($value)->slug('_'))->exists()) {

            $slug = $this->incrementSlug($slug);
        }

        $this->attributes['slug'] = $slug;
    }

    public function tags() : MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable' );
    }
}
