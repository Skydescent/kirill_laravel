<?php

namespace App\Http\Controllers\Public;

use App\Contracts\Repository\FeedbackRepositoryContract;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class FeedbacksController extends Controller
{

    /**
     * @param FeedbackRepositoryContract $repository
     * @return Application|Factory|View
     */
    public function index(FeedbackRepositoryContract $repository): View|Factory|Application
    {
        $feedbacks = $repository->getFeedbacks();
        return view('admin.feedbacks.index', compact( 'feedbacks'));
    }

    /**
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        return view('feedbacks.create');
    }

    /**
     * @param Request $request
     * @param FeedbackRepositoryContract $repository
     * @return Application|RedirectResponse|Redirector
     */
    public function store(
        Request $request,
        FeedbackRepositoryContract $repository
    ): Redirector|RedirectResponse|Application
    {
        $repository->store($request->validate([
            'email' => 'required|email',
            'body' => 'required'
        ]));
        flash('Мы ответим на ваше обращение в ближайшее время!');
        return redirect()->route('posts.index');
    }
}
