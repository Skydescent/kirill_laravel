<?php

namespace App\Http\Controllers\Public;

use App\Contracts\Service\CreateStepServiceContract;
use App\Contracts\Repository\RepositoryStepableContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskStepsController
{
    private RepositoryStepableContract $stepableRepository;

    public function __construct(RepositoryStepableContract $stepableRepository)
    {
        $this->stepableRepository = $stepableRepository;
    }

    public function store(
        Request $request,
        CreateStepServiceContract $createStepService,
        $id
    ): RedirectResponse
    {
        $createStepService->create(
            $request->validate(['description' => 'required|min:5']),
            $id,
            $this->stepableRepository);

        return back();
    }
}
