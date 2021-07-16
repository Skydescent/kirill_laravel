<?php

namespace Database\Factories;

use App\Models\News;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tag::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
//        $taggable = [
//            User::class,
//            Post::class,
//            Task::class,
//            News::class,
//        ];
//
//        return [
//            'noteable_id' => $faker->numberBetween(0,20),
//            'noteable_type' => $this->faker->randomElement($taggable),
//        ];

        return [
            'name' => $this->faker->unique()->word()
        ];
    }
}
