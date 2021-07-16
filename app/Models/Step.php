<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Step extends Model
{
    public $guarded = [];

    protected $casts = [
        'completed' => 'boolean',
    ];


    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function complete($completed = true)
    {
        $this->update(['completed' => $completed]);
    }

    public function incomplete()
    {
        $this->complete(false);
    }
}
