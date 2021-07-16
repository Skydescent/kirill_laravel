<?php


namespace App\Repositories\Eloquent;

use App\Contracts\Service\CacheServiceContract;
use App\Contracts\Repository\NewsRepositoryContract;
use App\Models\News;
use Illuminate\Database\Eloquent\Model;

class NewsRepository implements NewsRepositoryContract
{
    private CacheServiceContract $cacheService;

    public function __construct(CacheServiceContract $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function getNews(int $newsCount, string $currentPage)
    {
        $getNewsCallback = function () use ($newsCount) {
            return News::latest()->with('tags')->where('published', 1)->simplePaginate($newsCount);
        };

        $cacheKey = 'news|page=' . $currentPage;
        $tags = ['news_collection'];

        return $this->cacheService->cache($tags, $cacheKey, 600, $getNewsCallback);

    }

    public function getAdminNews(int $newsCount, string $currentPage)
    {
        $getAdminNews = function () use ($newsCount) {
            return News::latest()->paginate($newsCount);
        };

        $cacheKey = 'news|admin_panel|page=' . $currentPage;
        $tags = ['news_collection'];

        return $this->cacheService->cache($tags, $cacheKey, 600, $getAdminNews);
    }

    public function find($slug) : Model
    {
        $getPostCallback = function () use ($slug) {
            return News::with(['comments.author'])->firstWhere(['slug' => $slug]);
        };

        $cacheKey = 'news|news=' . $slug;
        $tags = ['news'];

        return $this->cacheService->cache($tags, $cacheKey, 600, $getPostCallback);

    }

    public function store(array $attributes) : Model
    {
        $news = News::create($attributes);
        $this->cacheService->flushCollections(['news_collection']);

        return $news;
    }

    public function update(array $attributes, array $identifier): Model
    {
        $slug = $identifier[array_key_first($identifier)];

        $news = News::firstWhere($identifier);
        $news->update($attributes);
        $this->cacheService->forget(['news'], 'news|news=' . $slug);
        $this->cacheService->flushCollections(['news_collection']);

        return $news;
    }

    public function delete($slug) : Model
    {
        $news = News::firstWhere(['slug' => $slug]);
        $news->delete();
        $this->cacheService->forget(['news'], 'news|news=' . $slug);
        $this->cacheService->flushCollections(['news_collection']);

        return $news;
    }


    public function addComment(array $attributes, string $commentableIdentifier)
    {
        $news = $this->find($commentableIdentifier);
        $news->comments()->create($attributes);
        $this->cacheService->forget(['news'], 'news|news=' . $commentableIdentifier);

    }
}