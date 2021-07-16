<?php

namespace App\Http\Middleware;

use App\Contracts\PostRepositoryContract;
use App\Service\Serviceable;
use Closure;
use Illuminate\Http\Request;

class BindModelFromCache
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param $model
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $repositoryContract, $requestFieldName): mixed
    {
        $repository = app()->make($repositoryContract);


        if (isset($request->$requestFieldName)) {
            $identifier = $request->$requestFieldName;
            $modelInstance = $repository->find($identifier);
            //$request->attributes->set($requestFieldName, $modelInstance);
            $request->$requestFieldName = $modelInstance;
        }

        return $next($request);
    }
}
