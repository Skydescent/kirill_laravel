<?php

namespace App\Models;

use App\Events\PostCreated;
use App\Events\PostUpdated;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @method static where(string $string, int $int)
 */
class Post extends Model implements Commentable, Taggable
{
    protected static function booted()
    {
        static::updated(function (Post $post) {
            broadcast(new PostUpdated($post, cachedUser()));
        });
        static::created(function (Post $post) {
            event(new PostCreated($post));
        });
    }

    protected static function boot()
    {
        parent::boot();

            static::updating(function($post) {
		    $post->history()->attach(getUserId(),
                [
                    'changed_fields' =>json_encode(array_keys($post->getDirty())),
                ]
            );
        });
    }

    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function queryFilter($query)
    {
            return $query->orWhereHas('posts', function (Builder $subQuery) {
                $subQuery
                    ->where('published', 1)
                    ->orWhere('owner_id', '=', getUserId());
            });
    }

    public function postQueryFilter($query)
    {
        return $query
            ->where('published', 1)
            ->orWhere('owner_id', '=', cachedUser()->id);
    }

    /**
     * @return MorphToMany
     */
    public function tags() : MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable' );
    }

    /**
     * @return BelongsTo
     */
    public function owner() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsToMany
     */
    public function history() : BelongsToMany
    {
        return $this
            ->belongsToMany(User::class, 'post_histories')
            ->withPivot(['changed_fields'])
            ->withTimestamps();
    }

    /**
     * @param $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function addComment($attributes) : \Illuminate\Database\Eloquent\Model
    {
        return $this
            ->comments()
            ->create(array_merge(
                $attributes,
                ['post_id' => $this->id]
            ));
    }
}
