<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $ability, ...$models)
    {
        foreach ($models as $model) {
            $result = $request->route($model, null) ?:
                ((preg_match("/^['\"](.*)['\"]$/", trim($model), $matches)) ? $matches[1] : null);
            Log::info('Authorize@getGateArguments result: ' . $result . ' ability: ' . $ability);
            return $next($request);
        }
    }
}
