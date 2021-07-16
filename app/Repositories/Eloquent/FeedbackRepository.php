<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Service\CacheServiceContract;
use App\Contracts\Repository\FeedbackRepositoryContract;
use App\Models\Feedback;
use Illuminate\Database\Eloquent\Model;

class FeedbackRepository implements FeedbackRepositoryContract
{
    private CacheServiceContract $cacheService;


    public function __construct(CacheServiceContract $cacheService)
    {
        $this->cacheService = $cacheService;
    }


    public function getFeedbacks()
    {
        $getFeedbacksCallback = function () {
            return  Feedback::latest()->get();
        };

        $cacheKey = 'feedbacks';
        $tags = ['feedbacks_collection'];

        return $this->cacheService->cache($tags, $cacheKey, 600, $getFeedbacksCallback);

    }

    public function store(array $attributes) : Model
    {
        $feedback = Feedback::create($attributes);
        $this->cacheService->flushCollections(['feedbacks_collection']);

        return $feedback;
    }

}