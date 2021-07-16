<?php

namespace App\Providers;

use App\Contracts\Repository\FeedbackRepositoryContract;
use App\Contracts\Repository\NewsRepositoryContract;
use App\Contracts\Repository\PostRepositoryContract;
use App\Contracts\Repository\StatisticsRepositoryContract;
use App\Contracts\Repository\TagRepositoryContract;
use App\Contracts\Repository\TaskRepositoryContract;
use App\Contracts\Repository\UserRepositoryContract;
use App\Repositories\Eloquent\FeedbackRepository;
use App\Repositories\Eloquent\NewsRepository;
use App\Repositories\Eloquent\PostRepository;
use App\Repositories\Eloquent\StatisticsRepository;
use App\Repositories\Eloquent\TagRepository;
use App\Repositories\Eloquent\TaskRepository;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(PostRepositoryContract::class, PostRepository::class);
        $this->app->singleton(NewsRepositoryContract::class, NewsRepository::class);
        $this->app->singleton(TaskRepositoryContract::class, TaskRepository::class);
        $this->app->singleton(UserRepositoryContract::class, UserRepository::class);
        $this->app->singleton(TagRepositoryContract::class, TagRepository::class);
        $this->app->singleton(FeedbackRepositoryContract::class, FeedbackRepository::class);
        $this->app->singleton(StatisticsRepositoryContract::class, StatisticsRepository::class);
    }
}
