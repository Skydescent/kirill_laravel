<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Model extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;

    public $guarded = [];

    public function incrementSlug($slug) {

        $original = $slug;

        $count = 2;

        while (static::whereSlug($slug)->exists()) {

            $slug = "{$original}_" . $count++;
        }

        return $slug;

    }
}
