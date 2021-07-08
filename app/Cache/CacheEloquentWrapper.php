<?php


namespace App\Cache;

use App\Service\Eloquent\CacheService;
use App\Models\User;
use ArrayAccess;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorInterface;
use Illuminate\Contracts\Pagination\Paginator as PaginatorInterface;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use JsonSerializable;

class CacheEloquentWrapper implements UrlRoutable, ArrayAccess, Arrayable, Jsonable, JsonSerializable
{

    protected array $identifier;

    protected ?User $user;

    private Model $model;

    protected CacheService $cacheService;

    public static function wrapModel(
        $model,
        array $identifier,
        CacheService $cacheService,
        ?User $user
    ): ?static
    {
        if (is_null($model)) return null;

        $instance = new static();
        $instance->model = $model;
        $instance->user = $user;
        $instance->identifier = $identifier;
        $instance->cacheService = $cacheService;

        return $instance;
    }

    public static function wrapCollection(
        Collection|EloquentCollection $collection,
        CacheService          $cacheService,
        string                        $keyName,
        ?User                         $user
    ): EloquentCollection|Collection
    {
        if($collection->first()) {
            return $collection
                ->map(function ($model) use ($cacheService, $keyName, $user) {
                    return static::wrapModel(
                        $model,
                        [$keyName => $model->$keyName],
                        $cacheService,
                        $user
                    );
                });
        }
       return collect();
    }

    public static function wrapPaginator(
        Paginator|LengthAwarePaginator $paginator,
        CacheService           $cacheService,
        string                         $keyName,
        ?User                          $user
    ): Paginator|LengthAwarePaginator
    {
        $modelsCollection = static::wrapCollection(
            $paginator->getCollection(),
            $cacheService,
            $keyName,
            $user
        );
        return $paginator->setCollection($modelsCollection);
    }

    public static function getWrapper($cache, $identifier, $cacheService, $user)
    {
        if (is_subclass_of($cache, Model::class) ) {
            return static::wrapModel($cache, $identifier, $cacheService, $user);
        }

        $indexInterfaces = class_implements($cache);
        $paginatorInterfaces = [PaginatorInterface::class, LengthAwarePaginatorInterface::class];
        $collectionInterfaces = [Enumerable::class];
        $keyName = array_key_first($identifier);

        if(count(array_intersect($paginatorInterfaces, $indexInterfaces)) !== 0) {
            return static::wrapPaginator($cache, $cacheService, $keyName, $user);
        }

        if (count(array_intersect($collectionInterfaces, $indexInterfaces)) !== 0) {
            return static::wrapCollection($cache,$cacheService, $keyName, $user);
        }
    }

    public function __get(string $name)
    {
        //Log::info('CachedEloquentWrapper@__get name:' . $name);
        if (in_array($name, $this->cacheService->getRelationsNames())) {

            $getRelation = function () use ($name) {
                return $this->model->$name;
            };
            //Log::info('CachedEloquentWrapper@__get cache:' . implode(',', $this->identifier));
            $cache = $this
                ->cacheService
                ->cache(
                    $getRelation,
                    $this->user,
                    array_merge($this->identifier, ['relation' => $name])
                );

            return static::getWrapper($cache,$this->identifier, $this->cacheService, cachedUser());
        }

        if($name === 'model') {
            return $this->getModel();
        }

        return $this->model->$name;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function __call(string $name, array $arguments)
    {
        return $this->model->{$name}($arguments);
    }

    public function __toString()
    {
        return json_encode($this->model);
    }

    public function toArray(): array
    {
        return $this->model->toArray();
    }

    public function toJson($options = 0)
    {
        $json = json_encode($this->jsonSerialize(), $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw JsonEncodingException::forModel($this, json_last_error_msg());
        }

        return $json;
    }

    public function jsonSerialize ()
    {
        return $this->model->toArray();
    }

    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        return $this->model->getRouteKey();
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return $this->model->getRouteKeyName();
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param mixed $value
     * @param string|null $field
     * @return Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->model->resolveRouteBinding($value, $field);
    }

    /**
     * Retrieve the child model for a bound value.
     *
     * @param string $childType
     * @param mixed $value
     * @param string|null $field
     * @return Model|null
     */
    public function resolveChildRouteBinding($childType, $value, $field)
    {
        $this->model->resolveChildRouteBinding($childType, $value, $field);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
       return $this->model->offsetExists($offset);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->model->offsetGet($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        return $this->model->offsetSet($offset, $value);
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        return $this->model->offsetSet($offset);
    }
}