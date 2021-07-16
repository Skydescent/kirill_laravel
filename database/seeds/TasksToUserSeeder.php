<?php

namespace Database\Seeders;

use App\Models\Step;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TasksToUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()
            ->create(
                [
                    'email' => 'kirill@laravel.com',
                    'password' => \Hash::make('1234')
                ]
            );

        Task::factory()
            ->times(5)
            ->create(['owner_id' => $user]) // в атрибутах первый пользователь
            ->each(function (\App\Models\Task $task) {
                $task->steps()->saveMany(Step::factory()->times(rand(1, 5))->make());
            });
    }
}
