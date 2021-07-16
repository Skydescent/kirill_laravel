<?php

namespace App\Models;

use App\Events\TaskCreated;
use App\Events\TaskUpdated;

class Task extends Model implements Taggable, Stepable
{
    public $guarded = [];

    protected $dispatchesEvents = [
        'created' => TaskCreated::class,
        'updated' => TaskUpdated::class,
    ];

    public function getRouteKeyName()
    {
        return 'id';
    }

    public function scopeIncompleted($query)
    {
        return $query->where('completed', 0);
    }

    public function steps()
    {
        return $this->hasMany(Step::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function addStep(array $attributes)
    {
        return $this->steps()->create($attributes);
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function isCompleted()
    {
        return (bool) $this->completed;
    }

    public function isNotCompleted()
    {
        return !$this->completed;
    }

    public function newCollection(array $models = [])
    {
        return new TasksCollection($models);
    }

}

