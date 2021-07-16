<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Service\CacheServiceContract;
use App\Contracts\Repository\StatisticsRepositoryContract;
use App\Models\News;
use App\Models\Post;
use App\Models\User;
use DB;

class StatisticsRepository implements StatisticsRepositoryContract
{
    private CacheServiceContract $cacheService;

    public function __construct(CacheServiceContract $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function getStatistics(): array
    {
        return array_merge($this->getPostsStatistics(), $this->getNewsStatistics());
    }

    private function getPostsStatistics()
    {
        $getPostsStatisticsCallback = function () {
            $activeUsers =  User::has('posts', '>', 1)->withCount('posts');
            return [
                'postsCount' => Post::count(),
                'maxPostsCountAuthorName' => User::withCount('posts')
                    ->orderByDesc('posts_count')
                    ->first()['name'],
                'longestPost' => Post::where('published', 1)
                    ->select(DB::raw('LENGTH(body) as len_body, title, slug'))
                    ->orderBy('len_body', 'desc')
                    ->first(),
                'shortestPost' => Post::where('published',1)
                    ->select(DB::raw('LENGTH(body) as len_body, title, slug'))
                    ->orderBy('len_body', 'asc')
                    ->first(),
                'mostChangedPost' => Post::withCount('history')
                    ->orderByDesc('history_count')
                    ->first(),
                'mostCommentedPost' => Post::withCount('comments')
                    ->orderByDesc('comments_count')
                    ->first(),
                'activeUsersAvgPosts' => floor(DB::table(DB::raw("({$activeUsers->toSql()}) as active"))
                    ->avg('posts_count'))
            ];
        };

        $cacheKey = 'posts_statistics';
        $tags = ['posts_collection'];

        return $this->cacheService->cache($tags, $cacheKey, 600, $getPostsStatisticsCallback);
    }

    private function getNewsStatistics()
    {
        $getNewsStatisticsCallback = function () {
            return [
                'newsCount' => News::count(),
            ];
        };
        $cacheKey = 'news_statistics';
        $tags = ['news_collection'];

        return $this->cacheService->cache($tags, $cacheKey, 600, $getNewsStatisticsCallback);
    }

}