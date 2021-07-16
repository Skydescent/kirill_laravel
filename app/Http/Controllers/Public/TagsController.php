<?php

namespace App\Http\Controllers\Public;

use App\Contracts\Repository\TagRepositoryContract;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class TagsController extends Controller
{

    public function index(TagRepositoryContract $repository, $name): Factory|View|Application
    {
        $tag = $repository->find($name);
        $publicModels = config('tags.models_with_tags');
        return view('tags.index', compact('tag', 'publicModels'));
    }
}
