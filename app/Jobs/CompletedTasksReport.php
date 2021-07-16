<?php

namespace App\Jobs;

use App\Models\Step;
use App\Models\Task;
use App\Models\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class CompletedTasksReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected User $owner;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user = null)
    {
        $this->owner = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tasksCount = Task::when(null !== $this->owner, function ($query) {
            $query->when('owner_id', $this->owner->id);
        })
            ->completed() //должен быть scope
            ->count()
        ;
        $stepsCount = Step::when(null !== $this->owner, function ($query) {
            $query->whereHas('owner', function ($query) {
                $query->where('users.id', '=', $this->owner->id);
            });
        })
            ->completed() //должен быть scope
            ->count()
        ;

        echo ($this->owner ? $this->owner->name : 'Всего') . ": Выполненных шагов: $stepsCount, Выполненных задач: $tasksCount";
    }

    public function failed(Exception $exception)
    {
        Log::error($exception->getMessage());
    }
}
