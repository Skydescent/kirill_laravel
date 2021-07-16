<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TagToTaggablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $tags = Tag::factory()
            ->times(50)
            ->create();

        $taggable = collect([
            User::class,
            Post::class,
            News::class,
            Task::class,
        ]);

        $taggable->each(function ($model){
           if ($model::all()->isEmpty())  $model::factory(50)->create();
        });

        for($i = 1; $i <= rand(400,500); $i++){
            $model = $taggable->random(1)->first();
            $item = $model::inRandomOrder()->first();
            $tag = $tags->random(1)->first();
             if (!$item->tags->contains($tag)) $item->tags()->save($tag);
        }
    }
}
