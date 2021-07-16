<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class PostsToUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userIds = User::factory(rand(2,4))->create()->pluck('id')->toArray();

        $tags = Tag::factory(rand(5,10))->create();


        for($i = 1; $i <= rand(20,25); $i++){
            Post::factory()
                ->create(['owner_id' =>$userIds[array_rand($userIds)]])
                ->tags()->saveMany($tags->random(3));
        }
    }
}
