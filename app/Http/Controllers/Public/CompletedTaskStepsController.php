<?php

namespace App\Http\Controllers\Public;

use App\Contracts\Repository\RepositoryStepableContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class CompletedTaskStepsController extends Controller
{

    private RepositoryStepableContract $taskRepository;

    public function __construct(RepositoryStepableContract $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function store($stepId, $taskId): RedirectResponse
    {
        $this->taskRepository->completeStep($stepId, $taskId);
        return back();
    }

    public function destroy($stepId, $taskId): RedirectResponse
    {
        $this->taskRepository->incompleteStep($stepId, $taskId);
        return back();
    }
}
