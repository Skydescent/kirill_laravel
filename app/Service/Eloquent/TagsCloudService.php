<?php

namespace App\Service\Eloquent;

use App\Contracts\Repository\TagRepositoryContract;
use App\Contracts\Service\Tag\TagsCloudServiceContract;

class TagsCloudService implements TagsCloudServiceContract
{
    private TagRepositoryContract $tagRepository;

    public function __construct(TagRepositoryContract $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function tagsCloud(string $userId =  null)
    {
        $modelsWithTags = config('tags.models_with_tags');

        $filter = $this->getFilterCallback($modelsWithTags);

        $tagCloudRelations = array_column($modelsWithTags, 'relation');

        return $this->tagRepository->tagsCloud($filter, $userId , $tagCloudRelations);

    }

    protected function getFilterCallback(array $modelsWithTags) : callable
    {
        return function ($query) use ($modelsWithTags)
        {
            return $this->getModelQueryFilter($modelsWithTags,$query);
        };
    }

    protected function getModelQueryFilter($models, $query, $queryFilterName = 'queryFilter')
    {
        foreach ($models as $model => $options){
            if(method_exists( new $model(), $queryFilterName)) {
                $query = call_user_func_array([new $model(), $queryFilterName], [$query]);
            } else {
                $query = $query->orHas($options['relation']);
            }
        }
        return $query;
    }
}