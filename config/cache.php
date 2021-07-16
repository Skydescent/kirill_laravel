<?php

use App\Models\Comment;
use App\Models\Company;
use App\Models\Feedback;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\PostsController as AdminPostsController;
use App\Http\Controllers\Public\CompletedTaskStepsController;
use App\Http\Controllers\Public\FeedbacksController;
use App\Http\Controllers\Public\NewsCommentsController;
use App\Http\Controllers\Public\NewsController as PublicNewsController;
use App\Http\Controllers\Public\PostCommentsController;
use App\Http\Controllers\Public\PostsController as PublicPostsController;
use App\Http\Controllers\Public\StatisticsController;
use App\Http\Controllers\Public\TagsController;
use App\Http\Controllers\Public\TasksController;
use App\Http\Controllers\Public\TaskStepsController;
use App\Models\News;
use App\Models\Post;
use App\Models\PostHistory;
use App\Service\AdminServiceable;
use App\Service\CommentsInterface;
use App\Service\Eloquent\CommentsService;
use App\Service\Eloquent\FeedbacksService;
use App\Service\Eloquent\NewsService;
use App\Service\Eloquent\PostsService;
use App\Service\Indexable;
use App\Service\Serviceable;
use App\Service\StatisticsService;
use App\Service\StepsInterface;
use App\Service\Eloquent\StepsService;
use App\Service\Eloquent\SyncTagsService;
use App\Service\TagsInterface;
use App\Service\Eloquent\TasksService;
use App\Models\Step;
use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache connection that gets used while
    | using this caching library. This connection is used when another is
    | not explicitly specified when executing a given caching function.
    |
    | Supported: "apc", "array", "database", "file",
    |            "memcached", "redis", "dynamodb"
    |
    */

    'default' => env('CACHE_DRIVER', 'file'),

    /*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the cache "stores" for your application as
    | well as their drivers. You may even define multiple stores for the
    | same cache driver to group types of items stored in your caches.
    |
    */

    'stores' => [

        'apc' => [
            'driver' => 'apc',
        ],

        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],

        'database' => [
            'driver' => 'database',
            'table' => 'cache',
            'connection' => null,
        ],

        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
        ],

        'memcached' => [
            'driver' => 'memcached',
            'persistent_id' => env('MEMCACHED_PERSISTENT_ID'),
            'sasl' => [
                env('MEMCACHED_USERNAME'),
                env('MEMCACHED_PASSWORD'),
            ],
            'options' => [
                // Memcached::OPT_CONNECT_TIMEOUT => 2000,
            ],
            'servers' => [
                [
                    'host' => env('MEMCACHED_HOST', '127.0.0.1'),
                    'port' => env('MEMCACHED_PORT', 11211),
                    'weight' => 100,
                ],
            ],
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'cache',
        ],

        'dynamodb' => [
            'driver' => 'dynamodb',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'table' => env('DYNAMODB_CACHE_TABLE', 'cache'),
            'endpoint' => env('DYNAMODB_ENDPOINT'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    |
    | When utilizing a RAM based store such as APC or Memcached, there might
    | be other applications utilizing the same cache. So, we'll specify a
    | value to get prefixed to all our keys so we can avoid collisions.
    |
    */

    'prefix' => env('CACHE_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_cache'),

];

