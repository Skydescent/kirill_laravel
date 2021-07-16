<?php

namespace App\Providers;

use App\Contracts\Repository\RepositoryStepableContract;
use App\Contracts\Service\CacheServiceContract;
use App\Contracts\Service\CreateStepServiceContract;
use App\Contracts\Service\News\CreateNewsServiceContract;
use App\Contracts\Service\News\DestroyNewsServiceContract;
use App\Contracts\Service\News\UpdateNewsServiceContract;
use App\Contracts\Service\Post\CreatePostServiceContract;
use App\Contracts\Service\Post\DestroyPostServiceContract;
use App\Contracts\Service\Post\UpdatePostServiceContract;
use App\Contracts\Service\Tag\SyncTagsServiceContract;
use App\Contracts\Service\Tag\TagsCloudServiceContract;
use App\Contracts\Service\Task\CreateTaskServiceContract;
use App\Contracts\Service\Task\UpdateTaskServiceContract;
use App\Http\Controllers\Public\CompletedTaskStepsController;
use App\Http\Controllers\Public\TaskStepsController;
use App\Repositories\Eloquent\TaskRepository;
use App\Service\Cache\CacheService;
use App\Service\Eloquent\CreateNewsService;
use App\Service\Eloquent\CreatePostService;
use App\Service\Eloquent\CreateStepService;
use App\Service\Eloquent\CreateTaskService;
use App\Service\Eloquent\DestroyNewsService;
use App\Service\Eloquent\DestroyPostService;
use App\Service\Eloquent\SyncTagsService;
use App\Service\Eloquent\TagsCloudService;
use App\Service\Eloquent\UpdateNewsService;
use App\Service\Eloquent\UpdatePostService;
use App\Service\Eloquent\UpdateTaskService;
use App\View\Components\Alert;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Collection::macro('toUpper', function() {
            return $this->map(function ($item) {
                return Str::upper($item);
            });
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->app->singleton(CacheServiceContract::class, CacheService::class);
        $this->app->singleton(SyncTagsServiceContract::class, SyncTagsService::class);
        $this->app->singleton(TagsCloudServiceContract::class, TagsCloudService::class);

        $this->app->singleton(CreatePostServiceContract::class, CreatePostService::class);
        $this->app->singleton(CreateTaskServiceContract::class, CreateTaskService::class);
        $this->app->singleton(CreateNewsServiceContract::class, CreateNewsService::class);
        $this->app->singleton(CreateStepServiceContract::class, CreateStepService::class);

        $this->app->singleton(UpdatePostServiceContract::class, UpdatePostService::class);
        $this->app->singleton(UpdateTaskServiceContract::class, UpdateTaskService::class);
        $this->app->singleton(UpdateNewsServiceContract::class, UpdateNewsService::class);

        $this->app->singleton(DestroyPostServiceContract::class, DestroyPostService::class);
        $this->app->singleton(DestroyNewsServiceContract::class, DestroyNewsService::class);

        $this->app->when([TaskStepsController::class, CompletedTaskStepsController::class])
            ->needs(RepositoryStepableContract::class)
            ->give(TaskRepository::class);

        view()->composer('layout.sidebar', function($view) {
            $tagsCloud = $this->app->get(TagsCloudServiceContract::class)->tagsCloud(getUserId());
            $view->with('tagsCloud', $tagsCloud);
        });


        Blade::component('alert', Alert::class);
        Blade::directive('datetime', function ($value) {
            return "<?php echo ($value)->format('d.m.Y') ?>";
        });
        Blade::if('admin', function ($user) { return $user && $user->isAdmin(); });

        Paginator::defaultSimpleView('pagination::simple-default');
        Paginator::defaultView('pagination::bootstrap-4');

        Relation::morphMap([
            'tasks' => 'App\Models\Task',
            'posts' => 'App\Models\Post',
            'news' => 'App\Models\News',
            'users' => 'App\Models\User',
        ]);


    }
}
