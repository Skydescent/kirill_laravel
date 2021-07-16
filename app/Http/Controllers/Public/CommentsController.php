<?php

namespace App\Http\Controllers\Public;

use App\Contracts\Repository\NewsRepositoryContract;
use App\Contracts\Repository\PostRepositoryContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentsController extends Controller
{

    public function storePostComment(
        Request $request,
        PostRepositoryContract $postRepository,
        $slug
    ): RedirectResponse
    {
        $attributes = $request->validate(['body' => 'required']);
        $attributes['author_id'] = getUserId();
        $postRepository->addComment($attributes, $slug);

        return back();
    }

    public function storeNewsComment(
        Request $request,
        NewsRepositoryContract $newsRepository,
        $slug
    ): RedirectResponse
    {
        $attributes = $request->validate(['body' => 'required']);
        $attributes['author_id'] = getUserId();
        $newsRepository->addComment($attributes, $slug);

        return back();
    }
}