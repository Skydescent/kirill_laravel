<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Service\CacheServiceContract;
use App\Contracts\Repository\PostRepositoryContract;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;

class PostRepository implements PostRepositoryContract
{
    private CacheServiceContract $cacheService;

    public function __construct(CacheServiceContract $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function find($slug): Model
    {
        $getPostCallback = function () use ($slug) {
            return Post::with(['comments.author', 'history'])->firstWhere(['slug' => $slug]);
        };

        $cacheKey = 'posts|post=' . $slug;
        $tags = ['posts'];

        return $this->cacheService->cache($tags, $cacheKey, 600, $getPostCallback);
    }


    public function store(array $attributes) : Model
    {
        $post =  Post::create($attributes);
        $this->cacheService->flushCollections(['posts_collection']);

        return $post;
    }

    public function update(array $attributes, array $identifier): Model
    {
        $slug = $identifier[array_key_first($identifier)];

        $post = Post::firstWhere($identifier);
        $post->update($attributes);

        $this->cacheService->forget(['posts'], 'posts|post=' . $slug);
        $this->cacheService->flushCollections(['posts_collection']);

        return $post;
    }

    public function delete($slug) : Model
    {
       $post = Post::firstWhere(['slug' => $slug]);
       $post->delete();
       $this->cacheService->forget(['posts'], 'posts|post=' . $slug);
       $this->cacheService->flushCollections(['posts_collection']);

       return $post;
    }

    /**
     * @param int $postsCount
     * @param string $currentPage
     * @param int|null $userId
     */
    public function getPosts(int $postsCount, string $currentPage, int $userId = null)
    {
        $getPostsCallback = function () use ($userId, $postsCount) {
            return Post::latest()->with('tags')
                ->where('published', 1)
                ->orWhere('owner_id', '=', $userId)
                ->simplePaginate($postsCount);
        };

        $cacheKey = 'posts' . ($userId ? '|user=' . $userId : '') . '|page=' . $currentPage;
        $tags = ['posts_collection'];

        return $this->cacheService->cache($tags, $cacheKey, 600, $getPostsCallback);
    }

    public function getAdminPosts(int $postsCount, string $currentPage)
    {
        $getPostsCallback = function () use ($postsCount) {
            return Post::latest()->with('owner')->paginate($postsCount);
        };

        $cacheKey = 'posts|admin_panel|page=' . $currentPage;
        $tags = ['posts_collection'];

        return $this->cacheService->cache($tags, $cacheKey, 600, $getPostsCallback);
    }

    public function addComment(array $attributes, string $commentableIdentifier)
    {
        $news = $this->find($commentableIdentifier);
        $news->comments()->create($attributes);
        $this->cacheService->forget(['posts'], 'posts|post=' . $commentableIdentifier);

    }
}